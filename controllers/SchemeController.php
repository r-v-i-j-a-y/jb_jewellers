<?php

require_once './services/authentication.php';
class SchemeController extends SessionData
{
    public function index()
    {
        if (AuthMiddleware::handle()) {
            require_once "views/homePage.php";
            exit;
        }
        $this->view('homePage');
    }

}
