<?php
#funçao pra ler o arquivo .env com as credenciais do banco

function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env não encontrado");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);

        
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}
?>