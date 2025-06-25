<?php
require_once "session.php";

function auth_protect()
{
    session_start_if_needed();
    if (!session_has('auth')) {
        header("Location: login.php"); // adjust if your login URL is different
        exit;
    }
    return $_SESSION['auth'];
}
function auth_check()
{
    session_start_if_needed();
    if (session_has('auth')) {
        header("Location: index.php"); // adjust if your login URL is different
        exit;
    }
}

// function guest_only()
// {
//     session_start_if_needed();

//     if (session_has('auth')) {
//         header("Location: index.php"); // or home page
//         exit;
//     }
// }
