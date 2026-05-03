<?php

require_once __DIR__ . '/../../config/database.php';

class AuthController {

    public function login() {

        session_start();
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $usuario = $data['usuario'] ?? '';
        $senha = $data['senha'] ?? '';

        if (!$usuario || !$senha) {
            http_response_code(400);
            echo json_encode(["erro" => "Preencha usuário e senha"]);
            return;
        }

        try {

            $conn = Database::connect();

            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE login_usuario = ?");
            $stmt->execute([$usuario]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($senha, $user['senha_usuario'])) {

                $_SESSION['admin_logado'] = true;
                $_SESSION['admin_id'] = $user['id'];

                // 🔐 gera CSRF automaticamente no login
                require_once __DIR__ . '/../helpers/csrf.php';
                $csrf = gerarCsrfToken();

                echo json_encode([
                    "status" => "sucesso",
                    "mensagem" => "Login realizado com sucesso",
                    "csrf_token" => $csrf
                ]);

            } else {

                http_response_code(401);
                echo json_encode([
                    "status" => "erro",
                    "mensagem" => "Usuário ou senha inválidos"
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "erro" => "Erro interno",
                "detalhe" => $e->getMessage()
            ]);
        }
    }

    public function logout() {

        session_start();
        session_destroy();

        echo json_encode([
            "mensagem" => "Logout realizado"
        ]);
    }

    public function check() {

        session_start();

        if (isset($_SESSION['admin_logado'])) {
            echo json_encode(["logado" => true]);
        } else {
            http_response_code(401);
            echo json_encode(["logado" => false]);
        }
    }
}