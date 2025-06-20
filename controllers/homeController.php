<?php
class HomeController
{
    public function index()
    {
        $message = "Welcome to Core PHP MVC! vijay";
        require_once "views/homePage.php";
    }

}
