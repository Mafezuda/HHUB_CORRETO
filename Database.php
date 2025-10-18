<?php
namespace Healthhub\Emr\Config;

use PDO;
use PDOException;

final class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (!self::$instance) {
            $dsn = "mysql:host=localhost;dbname=healthhub;charset=utf8mb4";
            $user = "root";
            $pass = "";
            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Erro ao conectar no banco: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
