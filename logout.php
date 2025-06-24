<?php
require './functions/session.php';
session_destroy_all();
header("Location: login.php"); // adjust if your login URL is different
exit;

?>