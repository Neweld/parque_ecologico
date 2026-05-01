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
    }
}
?>