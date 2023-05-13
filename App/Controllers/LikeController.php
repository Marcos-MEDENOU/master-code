<?php
require_once('../App/Conf/Database.php');
require_once('../App/Models/Like.php');

class LikeController
{
    use Crypt;
    private $like, $articleid, $userid;

    // Ajout dans la bdd précisément dans la table likes 
    public function addLike()
    {
        @session_start();
        if ((isset($_GET["articleid"])) && (isset($_SESSION["Auth"]))) {
            $datas = file_get_contents("php://input");
            $datas = json_decode($datas);

            $this->articleid = $this->datadecrypt($_GET["articleid"]);
            $this->userid = $_SESSION["Auth"]["id"];

            $this->like = new Like();
            $array = $this->like->select($this->articleid, $this->userid);
            $count = count($array);

            if ($count > 0) {
                $this->like->deleteOneLikes($this->articleid, $this->userid);
                $result = $this->like->selectCount($this->articleid);
                $result = trim($result[0]["Nombre"]);
                echo json_encode($result);
            } else {
                $this->like->addLike($this->articleid, $this->userid);
                $result = $this->like->selectCount($this->articleid);
                $result = trim($result[0]["Nombre"]);
                echo json_encode($result);
            }
        }
    }

}