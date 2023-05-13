<?php
require_once('../App/Conf/Database.php');
require_once('../App/Models/Contact.php');
require_once('../App/Models/Adresse.php');

class ContactController
{
    use Crypt;
    private $contact, $adresse;
    // déclaration des variables
    private $id, $email, $name, $theme, $body, $timestamps;


    // Function vérifiant si les champs sont bien remplis
    public function verifyInputs()
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        $this->email = $this->sanitaze($data->email);
        $this->name = $this->sanitaze($data->name);
        $this->theme = $this->sanitaze($data->theme);
        $this->body = nl2br($data->corps);
        $this->emptyInputs();
    }

    // sanitaze(); pour les espacements et les injections de codes
    public function sanitaze($data)
    {
        $reg = preg_replace("/\s+/", " ", $data);
        $reg = preg_replace("/^\s*/", "", $reg);
        $reg = htmlspecialchars($reg);
        $reg = stripslashes($reg);
        $data = $reg;
        return $data;
    }

    // emptyInputs(), pour vérifiez si un des champs est vide
    public function emptyInputs()
    {
        if (empty($this->email) || empty($this->name) || empty($this->theme) || empty($this->body)) {
            echo json_encode("empty_input");
        } else {
            echo json_encode("valid_input");
        }
    }

    // verifyEmail(), pour vérifiez si l'email suis les normes pré-requises
    public function verifyEmail()
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        $this->email = $data->email;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode("email_invalid");
        } else {
            echo json_encode("email_valid");
        }
    }

    // Ajout des messages des utilisateurs dans la table `contact`
    public function addContact()
    {
        $data = file_get_contents("php://input");
        $data = json_decode($data);
        $this->contact = new Contact();
        $this->email = $this->sanitaze($data->email);
        $this->name = $this->sanitaze($data->name);
        $this->theme = $this->sanitaze($data->theme);
        $this->body = nl2br($data->corps);
        $this->timestamps = date("Y-m-d h:i:s");
        $datas = $this->contact->addContact($this->email, $this->name, $this->theme, $this->body, $this->timestamps);
        if ($datas === true) {
            $controller = "?goto=" . $this->datacrypt('home');
            $action = "action=" . $this->datacrypt('view-home');
            $url = $controller . "&" . $action;
            echo json_encode("$url");
        } else {
            echo json_encode("message_error");
        }
    }

    // Compte le nombre d'adresses venus sur le site
    public function countAdresse()
    {
        $this->adresse = new Adresse();
        return $this->adresse->countAdresse();
    }

    // Ajout des noms d'hôtes dans la table adresses
    public function insertIp()
    {
        $this->adresse = new Adresse();
        $datas = file_get_contents("php://input");
        $datas = json_decode($datas);
        $adresses = $this->datadecrypt($datas->adresse);
        $tableau = $this->adresse->getAllAdresse();
        if (count($tableau) > 0) {
            $tableau_decrypt = [];
            for ($i = 0; $i < count($tableau); $i++) {
                array_push($tableau_decrypt, $this->datadecrypt($tableau[$i]["nom_hote"]));
            }
            if (in_array($adresses, $tableau_decrypt)) {
                echo json_encode("Adresse existante !!!");
            } else {
                $this->adresse->addAdress($datas->adresse);
            }
        } else {
            $this->adresse->addAdress($datas->adresse);
        }
    }

    // Vérifie s'il y a des messages qui ont été répondus par l'admin
    public function stateValide()
    {
        // instanciation de la classe model contact
        $this->contact = new Contact();
        $result = "";

        $array = $this->contact->stateValide();

        if (count($array) > 0) {
            $result = "valide";
        } else {
            $result = "attente";
        }

        return $result;
    }

    // Affiche tous les messages envoyés par les utilisateurs via le site
    public function getAllContacts()
    {
        // instanciation de la classe model contact
        $this->contact = new Contact();
        $array = $this->contact->getAllContact();
        return $array;
    }

    // Affiche un message spécifique d'un utilisateur
    public function getOneContact()
    {
        if (isset($_GET["contactid"])) {
            // instanciation de la classe model contact
            $this->contact = new Contact();
            $this->id = $this->datadecrypt($_GET["contactid"]);
            $array = $this->contact->getOneContact($this->id);
        }
        return $array;
    }

    // Suppression des messages one by one
    public function deleteOneContact()
    {
        $datas = file_get_contents("php://input");
        $datas = json_decode($datas);
        $this->id = $this->datadecrypt($datas->id);

        // instanciation de la classe model contact
        $this->contact = new Contact();

        // Suppression d'un article
        $this->contact->deleteOneContact($this->id);
        echo json_encode("supprime");
    }

    // Cette fonction permet de répondre à un message et envoie ce dernier dans l'email de l'utilisateur
    public function sendMessage()
    {
        if (isset($_GET["contactid"])) {
            $datas = file_get_contents("php://input");
            $datas = json_decode($datas);
            $this->id = intval($this->datadecrypt($_GET["contactid"]));
            // instanciation de la classe model contact
            $this->contact = new Contact();
            $this->contact->updateOneStateValide($this->id);
            $to = $datas->mail;
            $subject = $this->sanitaze($datas->subject);
            $message = $datas->message;
            $headers = "From: Genius Blog" . "\r\n" . "CC: geniusblog@gmail.com";
            // NB: geniusblog@gmail.com, cet email dépend de l'hébergeur sur lequel se trouve notre site et doit être valide.
            mail($to, $subject, $message, $headers);
            $controller = "?goto=" . $this->datacrypt('dashboard');
            $action = "action=" . $this->datacrypt('message');
            $url = $controller . "&" . $action;
            echo json_encode("$url");
        }
    }

    // Compte par jour le nombre de messages reçus
    public function countDay()
    {
        $this->contact = new Contact();
        $array = $this->contact->getAllContactAttente();
        $message_day = count($array);
        return $message_day;
    }
}