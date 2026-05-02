<?php

class Database {
    private static $conn = null;
    private static $envLoaded = false;

    /**
     * Carrega o .env da raiz do projeto
     */
    private static function loadEnv($path) {

        if (!file_exists($path)) {
            throw new Exception(".env não encontrado em: " . $path);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            // ignora comentários
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value);

            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }

    /**
     * Retorna conexão PDO (Singleton)
     */
    public static function connect() {

        if (self::$conn === null) {

            // Carrega .env apenas uma vez
            if (!self::$envLoaded) {
                $envPath = realpath(__DIR__ . '/../.env');

                if ($envPath === false) {
                    throw new Exception("Caminho do .env inválido");
                }

                self::loadEnv($envPath);
                self::$envLoaded = true;
            }

            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');

            // validação básica
            if (!$host || !$dbname || !$user) {
                throw new Exception("Variáveis do .env não carregadas corretamente");
            }

            try {
                self::$conn = new PDO(
                    "mysql:host={$host};dbname={$dbname};charset=utf8",
                    $user,
                    $pass
                );

                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                throw new Exception("Erro na conexão: " . $e->getMessage());
            }
        }

        return self::$conn;
    }
}
?>