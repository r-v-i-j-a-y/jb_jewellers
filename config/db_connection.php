<?php
$env = parse_ini_file('.env');

$host = $env["HOST"];
$db = $env["DATABASE"];
$user = $env["USERNAME"];
$pass = $env["PASSWORD"];
$port = $env["PORT"];

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);

    // Optional: Throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Database connected via PDO";
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
