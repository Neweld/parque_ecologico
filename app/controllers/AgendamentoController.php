<?php

require_once __DIR__ . '/../core/ConnectionManager.php';
require_once __DIR__ . '/../models/Agendamento.php';

class AgendamentoController {

    private $agendamentoModel;
    private $conn;

    public function __construct() {
        $this->conn = ConnectionManager::getConnection();
        $this->agendamentoModel = new Agendamento($this->conn);
    }

    /**
     * Retorna todos os agendamentos em JSON
     */
    public function index() {
        header('Content-Type: application/json');
        $dados = $this->agendamentoModel->getAll();
        echo json_encode($dados);
    }

    /**
     * Cria um novo agendamento
     */
    public function store() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if ($this->agendamentoModel->create($data)) {
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

    /**
     * Aprova um agendamento pelo ID
     */
    public function aprovar($id) {
        header('Content-Type: application/json');
        $this->atualizarStatus($id, 'aprovado');
    }

    /**
     * Rejeita um agendamento pelo ID
     */
    public function rejeitar($id) {
        header('Content-Type: application/json');
        $this->atualizarStatus($id, 'rejeitado');
    }

    /**
     * Deleta um agendamento pelo ID
     */
    public function delete($id) {
        header('Content-Type: application/json');

        $stmt = $this->conn->prepare("DELETE FROM agendamento WHERE id=?");
        $stmt->execute([$id]);

        echo json_encode(["mensagem" => "Excluído com sucesso"]);
    }

    /**
     * Método auxiliar para atualizar status (Remove duplicação)
     */
    private function atualizarStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE agendamento SET status=? WHERE id=?");
        $stmt->execute([$status, $id]);

        echo json_encode([
            "mensagem" => ucfirst($status) . " com sucesso",
            "status" => $status
        ]);
    }

}

?>