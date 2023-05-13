<?php
require_once('../App/Conf/Database.php');
require_once('../App/Models/Article.php');
class ArticleController
{
    use Crypt;
    // Déclaration des variables
    private $article, $id, $title, $image, $code_html, $category_id, $user_id, $created_at;

    // Ajout des articles
    public function addArticles()
    {
        @session_start();
        if ((isset($_GET["categoryid"])) && (isset($_SESSION["Auth"]))) {
            $datas = file_get_contents("php://input");
            $datas = json_decode($datas);
            $this->code_html = $datas->code_html;
            $this->category_id = $this->datadecrypt($_GET["categoryid"]);
            $this->user_id = $this->datadecrypt($_SESSION["Auth"]["id"]);
            $this->created_at = date("Y-m-d h:i:s");

            // instanciation de la classe model article
            $this->article = new Article();

            // Je vérifie si dans le code html on a un ou plusieurs éléments src
            // preg_match_all("|src=(?<source>\"(.*)\")|U", $this->code_html, $matches_files, PREG_PATTERN_ORDER);
            // var_dump($matches_files);
            // $this->image = $matches_files["source"][0];
            // echo json_encode($this->image);
            $this->image = "";

            // Je vérifie si dans le code html on a un ou plusieurs éléments h2
            if (preg_match_all("|<h2>(?<title>(.*))</h2>|U", $this->code_html, $matches_title, PREG_PATTERN_ORDER)) {
                $this->title = $matches_title["title"][0];
                if (preg_match_all("|<[^>]+>(?<titre>.*)</[^>]+>|U", $this->title, $tab, PREG_PATTERN_ORDER)) {
                    $this->title = $tab["titre"][0];
                } else {
                    $this->title = $matches_title["title"][0];
                }
            }

            // Je vérifie si il y a un titre dans la bdd correspondant au nouveau qu'on veut insérer dans la bdd
            $array = $this->article->getTitlesArticle($this->title);
            $count = count($array);
            if ($count > 0) {
                echo json_encode("Article_exist");
            } else {
                // Insertion de l'article
                $insert = $this->article->addArticle($this->image, $this->title, $this->code_html, $this->category_id, $this->user_id, $this->created_at);
                $controller = "?goto=" . $this->datacrypt('dashboard');
                $action = "action=" . $this->datacrypt('articles');
                $url = $controller . "&" . $action;
                echo json_encode("$url");
            }
        }
    }

    // Suppression des articles one by one
    public function deleteOneArticle()
    {
        $datas = file_get_contents("php://input");
        $datas = json_decode($datas);
        $this->id = $this->datadecrypt($datas->id);

        // instanciation de la classe model article
        $this->article = new Article();

        // Suppression d'un article
        $array = $this->article->deleteOneArticle($this->id);
        echo json_encode("supprime");
    }

    // Affiche tous les articles
    public function getAllArticles()
    {
        // instanciation de la classe model article
        $this->article = new Article();
        $array = $this->article->getAllArticles();
        return $array;
    }

    // Pour afficher tous les titres des différents articles se trouvant dans la bdd
    public function getAllTitlesArticle()
    {
        // instanciation de la classe model article
        $this->article = new Article();
        $array = $this->article->getAllTitlesArticle();
        return $array;
    }

    // Tant qu'une catégorie a été choisie, il fait une redirection
    public function redirect()
    {
        $datas = file_get_contents("php://input");
        $datas = json_decode($datas);
        $categoryid = $this->datacrypt($datas->categoryid);
        $controller = "?goto=" . $this->datacrypt('dashboard');
        $action = "action=" . $this->datacrypt('editor');
        $id = "categoryid=" . $categoryid;
        $url = $controller . "&" . $id . "&" . $action;
        echo json_encode("$url");
    }

    // Compte le nombre d'articles qu'il y a dans la bdd
    public function countArticle()
    {
        // instanciation de la classe model article
        $this->article = new Article();
        $array = $this->article->countArticle();
        return $array;
    }
    
    // Connaître le pourcentage des utilisateurs venus dans ce mois
    public function statistique()
    {
        $date = date("Y-m");
        $tableau = [];
        $dat = [];
        $articles_month = 0;
        $this->article = new Article();
        $array = $this->article->getAllArticles();
        for ($i = 0; $i < count($array); $i++) {
           $el = explode(" ", $array[$i]["created_at"])[0];
           array_push($dat, $el);
        }
        for ($i = 1; $i <= date('t'); $i++) {
            if($i < 10)
            {
                $i = "0" . $i;
            }
           $element = $date . '-' . $i;
           array_push($tableau, $element);
        }
        for ($i = 0; $i < count($dat); $i++) {
            if(in_array($dat[$i], $tableau))
            {
                $articles_month += 1;
            }
        }
        // $pourcentage = (100 * $articles_month)/ count($array);
        $pourcentage = (100 * $articles_month)/ 1;

        return $pourcentage;
    }
}