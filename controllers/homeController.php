<?php

require_once './services/authentication.php';
class HomeController extends SessionData
{
    public function index()
    {
        if (AuthMiddleware::handle()) {
            require_once "views/homePage.php";
            exit;
        }
        $this->view('homePage', [
            'userName' => 'Vijay',
            'email' => 'vijay@example.com'
        ]);
        // require_once "views/homePage.php";
    }

}
