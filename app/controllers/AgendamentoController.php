<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Agendamento.php';

class AgendamentoController {

    private $agendamentoModel;
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
        $this->agendamentoModel = new Agendamento($this->conn);
    }



    


    /**
     * Retorna todos os agendamentos em JSON
     */
   public function index() {

        header('Content-Type: application/json');

        try {
            $data = $this->agendamentoModel->getAll();

            echo json_encode($data);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "erro" => "Erro ao listar",
                "detalhe" => $e->getMessage()
            ]);
        }
    }

    /**
     * Cria um novo agendamento
     */
    public function store() {

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["erro" => "JSON inválido"]);
            return;
        }

        // validação básica
        $required = [
            'nome_instituicao',
            'nome_responsavel',
            'email',
            'data_reserva',
            'faixa_etaria',
            'qtd_visitantes',
            'proposito_visita',
            'horario_entrada',
            'horario_saida'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(["erro" => "Campo obrigatório: $field"]);
                return;
            }
        }

        try {

            $success = $this->agendamentoModel->create($data);

            if ($success) {
                echo json_encode([
                    "mensagem" => "Agendamento enviado com sucesso"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "erro" => "Não foi possível salvar"
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