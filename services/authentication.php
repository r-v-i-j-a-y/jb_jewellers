<?php

require_once __DIR__ . "/session.php";
class AuthMiddleware
{
    public static function handle()
    {
        Session::start();

        if (!Session::has('auth')) {
            header("Location: login");
            exit;
        }
    }
}

class AuthChechMiddleware
{
    public static function handle()
    {
        Session::start();

        if (Session::has('auth')) {
            header("Location: /");
            exit;
        }
    }
}