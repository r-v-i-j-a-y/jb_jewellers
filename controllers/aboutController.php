<?php
require_once './services/authentication.php';
class AboutController extends SessionData
{

    public function index()
    {
        if (AuthMiddleware::handle()) {
            require_once "views/homePage.php";
            exit;
        }
        require_once "views/aboutPage.php";
    }
}
