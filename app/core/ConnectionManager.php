<?php

class ConnectionManager {
    private static $conn = null;

    /**
     * Retorna a conexão com o banco de dados (Singleton)
     */
    public static function getConnection() {
        if (self::$conn === null) {

            // Caminho absoluto para env.php
            require_once realpath(__DIR__ . '/env.php');

            // Caminho absoluto para o .env (raiz do projeto)
            loadEnv(realpath(__DIR__ . '/../../.env'));
            var_dump($_ENV);
exit;   

            // Recupera variáveis
            $host = getenv('DB_HOST');  
            $dbname = getenv('DB_NAME');
            $username = getenv('DB_USER');
            $password = getenv('DB_PASS');

            var_dump($host);
            var_dump($dbname);
            var_dump($username);
            var_dump($password);

            // Validação básica (evita erro silencioso)
            if (!$host || !$dbname || !$username) {
                throw new Exception("Variáveis de ambiente do banco não definidas corretamente");
            }

            try {
                self::$conn = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8",
                    $username,
                    $password
                );

                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                throw new Exception("Erro na conexão com o banco: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}

?>