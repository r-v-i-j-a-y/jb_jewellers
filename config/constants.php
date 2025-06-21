<?php
$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$host = "jb_jewellers.test/";
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

define('BASE_URL', $protocol . $host . $path);

