<?php
require './services/session.php';
class LogoutController extends SessionData
{
    public function index()
    {
        Session::destroy();

        header("Location: login");
    }

}
