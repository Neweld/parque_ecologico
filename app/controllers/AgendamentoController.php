<?php
    $conn = require '../config/database.php';
    require_once '../app/models/Agendamento.php';

class AgendamentoController {

   public function store() {

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);

    $conn = require '../config/database.php';
    $agendamento = new Agendamento($conn);

    if ($agendamento->create($data)) {

        http_response_code(201); 
        echo json_encode([
            "status" => "sucesso",
            "mensagem" => "Formulário enviado com sucesso!"
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "status" => "erro",
            "mensagem" => "Erro ao enviar formulário"
        ]);
    }
}

public function aprovar($id) {
    $conn = require '../config/database.php';

    $stmt = $conn->prepare("UPDATE agendamento SET status='aprovado' WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["mensagem" => "Aprovado"]);
}

public function rejeitar($id) {
    $conn = require '../config/database.php';

    $stmt = $conn->prepare("UPDATE agendamento SET status='rejeitado' WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["mensagem" => "Rejeitado"]);
}

public function delete($id) {
    $conn = require '../config/database.php';

    $stmt = $conn->prepare("DELETE FROM agendamento WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(["mensagem" => "Excluído"]);
}




    public function index() {
    $conn = require '../config/database.php';

    $agendamento = new Agendamento($conn);
    $dados = $agendamento->getAll();

    echo json_encode($dados);
}

    private function validar($data) {
        return isset($data['nome_responsavel'], $data['email'], $data['data_reserva']);
    }
}




?>