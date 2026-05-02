<?php

class AuthMiddleware {

    public static function handle() {


        

        session_start();

        if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {

            http_response_code(401);

            echo json_encode([
                "erro" => "Não autorizado"
            ]);

            exit();
        }

        require_once __DIR__ . '/../helpers/csrf.php';

        $headers = getallheaders();
        $token = $headers['X-CSRF-Token'] ?? '';

        if (!validarCsrfToken($token)) {
        http_response_code(403);
        echo json_encode(["erro" => "CSRF inválido"]);
        exit();
        }
    }
}
?>