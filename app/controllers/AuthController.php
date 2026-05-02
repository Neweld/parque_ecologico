<?php

class AuthController {

    public function login() {

        session_start();

        $data = json_decode(file_get_contents("php://input"), true);

        $usuario = $data['usuario'] ?? '';
        $senha = $data['senha'] ?? '';

        $conn = require '../config/database.php';

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE login_usuario = ?");
        $stmt->execute([$usuario]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha_usuario'])) {

            $_SESSION['admin_logado'] = true;
            $_SESSION['admin_id'] = $user['id'];

            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "Login realizado com sucesso"
            ]);

        } else {

            http_response_code(401);

            echo json_encode([
                "status" => "erro",
                "mensagem" => "Usuário ou senha inválidos"
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
?>