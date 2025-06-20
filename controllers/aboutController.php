<?php
class AboutController
{

    public function index()
    {
        $info = "This is the about page.";
        require_once "views/aboutPage.php";
    }
}
