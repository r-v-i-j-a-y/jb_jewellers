<?php
function db_connection()
{
    static $pdo = null;

    if ($pdo === null) {
        $env = parse_ini_file(__DIR__ . '/../.env');

        $host = $env['HOST'] ?? 'localhost';
        $db = $env['DATABASE'] ?? 'mydb';
        $user = $env['USERNAME'] ?? 'root';
        $pass = $env['PASSWORD'] ?? '';
        $port = $env['PORT'] ?? '3306';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("PDO Connection failed: " . $e->getMessage());
        }
    }

    return $pdo;
}
