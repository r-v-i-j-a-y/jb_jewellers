<?php
class DB
{
    private static $pdo;

    public static function connection()
    {
        if (!self::$pdo) {
            $env = parse_ini_file('.env');

            $host = $env['HOST'] ?? 'localhost';
            $db = $env['DATABASE'] ?? 'mydb';
            $user = $env['USERNAME'] ?? 'root';
            $pass = $env['PASSWORD'] ?? '';
            $port = $env['PORT'] ?? '3306';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("PDO Connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
