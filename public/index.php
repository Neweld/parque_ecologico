    <?php

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Não define content-type automaticamente - deixa os controllers decidirem
        // Se for uma página HTML, renderiza HTML
        // Se for uma API, renderiza JSON

        require_once '../app/core/Router.php';

        Router::route();
    
    ?>