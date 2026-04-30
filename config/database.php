<?php
    require_once __DIR__ . '/env.php';


loadEnv(__DIR__ . '/../.env');

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $conn;

} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}


?>