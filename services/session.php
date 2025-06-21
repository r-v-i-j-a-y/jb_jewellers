<?php

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function all()
    {
        self::start();
        return $_SESSION;
    }

    public static function destroy()
    {
        self::start();
        session_unset();
        session_destroy();
    }
}


class SessionData
{
    protected $auth = null;
    protected $auth_user_id;
    protected $auth_user_name;
    protected $auth_user_email;
    protected $auth_user_mobile;
    protected $auth_user_role_id;
    protected $auth_user_status;
    protected $auth_user_created_by;
    protected $auth_user_updated_by;
    protected $auth_user_created_at;

    public function __construct()
    {
        if (Session::get('auth')) {
            $this->auth = Session::get('auth');
            $this->auth_user_name = $this->auth['user_name'];
            $this->auth_user_id = $this->auth['id'];
            $this->auth_user_email = $this->auth['email'];
            $this->auth_user_mobile = $this->auth['mobile'];
            $this->auth_user_role_id = $this->auth['role_id'];
            $this->auth_user_status = $this->auth['status'];
            $this->auth_user_created_by = $this->auth['created_by'];
            $this->auth_user_updated_by = $this->auth['updated_by'];
            $this->auth_user_created_at = $this->auth['created_at'];
        }
    }

    protected function view($path, $data = [])
    {
        extract($data);
        require "views/{$path}.php";
    }

    protected function redirect($url)
    {
        header("Location: index.php?url=$url");
        exit;
    }
}




