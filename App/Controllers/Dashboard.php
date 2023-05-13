<?php
require_once("../App/Controllers/crypt.php");
require_once("../App/Controllers/CategoryController.php");
require_once("../App/Controllers/ArticleController.php");
require_once("../App/Controllers/UserController.php");
require_once("../App/Controllers/ContactController.php");
class Dashboard {
    use Crypt;
    use AdresseIp;
 
    public function category()
    {
        $categories = new CategoryController();
        $allCategories = $categories->allCategories();
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        require_once("../App/Views/admin/category_page.phtml");
    }

    public function users()
    {
        $users = new UserController();
        $allUsers = $users->getAllUsers();
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        require_once("../App/Views/admin/Users.phtml");
    }

    public function updateUsers()
    {
        $users = new UserController();
        $oneUser = $users->getOneUser();
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        require_once("../App/Views/admin/updateUsers.phtml");
    }

    public function articles()
    {
        $categories = new CategoryController();
        $allCategories = $categories->allCategories();
        $articles = new ArticleController();
        $allArticles = $articles->getAllArticles();
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        require_once("../App/Views/admin/articles.phtml");
    }

    public function editor()
    {
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        require_once("../App/Views/admin/editor.phtml");
    }

    public function message()
    {
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        $allContacts = $contacts->getAllContacts();
        $stateValide = $contacts->stateValide();
        require_once("../App/Views/admin/message.phtml");
    }

    public function sendResponse()
    {
        $contacts = new ContactController();
        $countDay = $contacts->countDay();
        $oneContact = $contacts->getOneContact();
        require_once("../App/Views/admin/send-message.phtml");
    }
   
}