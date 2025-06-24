<?php

function session_start_if_needed()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function session_set($key, $value)
{
    session_start_if_needed();
    $_SESSION[$key] = $value;
}

function session_get($key, $default = null)
{
    session_start_if_needed();
    return $_SESSION[$key] ?? $default;
}

function session_has($key)
{
    session_start_if_needed();
    return isset($_SESSION[$key]);
}

function session_remove($key)
{
    session_start_if_needed();
    unset($_SESSION[$key]);
}

function session_all()
{
    session_start_if_needed();
    return $_SESSION;
}

function session_destroy_all()
{
    session_start_if_needed();
    session_unset();
    session_destroy();
}
