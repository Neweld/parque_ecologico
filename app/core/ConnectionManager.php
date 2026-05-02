<?php

class ConnectionManager {
    private static $conn = null;

    /**
     * Retorna a conexão com o banco de dados
     * Implementa singleton pattern
     */
    public static function getConnection() {
        if (self::$conn === null) {
            require_once __DIR__ . '/../config/env.php';
            
            loadEnv(__DIR__ . '/../../.env');

            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $username = getenv('DB_USER');
            $password = getenv('DB_PASS');

            try {
                self::$conn = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8",
                    $username,
                    $password
                );

                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}

?>
