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



    private function validar($data) {

    // nome_instituicao
    if (empty($data['nome_instituicao']) || strlen($data['nome_instituicao']) < 3) {
        return "Nome da instituição deve ter pelo menos 3 caracteres";
    }

    // nome_responsavel
    if (empty($data['nome_responsavel']) || strlen($data['nome_responsavel']) < 3) {
        return "Nome do responsável deve ter pelo menos 3 caracteres";
    }

    // email
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return "Email inválido";
    }

    // data_reserva
    if (empty($data['data_reserva'])) {
        return "Data da reserva é obrigatória";
    }

    $diaSemana = date('N', strtotime($data['data_reserva']));
    if ($diaSemana >= 6) {
        return "A reserva deve ser em dias úteis (segunda a sexta)";
    }

    // faixa_etaria
    $validos = ['infantil', 'adolescente', 'adulto', 'misto'];
    if (empty($data['faixa_etaria']) || !in_array($data['faixa_etaria'], $validos)) {
        return "Selecione uma faixa etária válida";
    }

    // qtd_visitantes
    if (!isset($data['qtd_visitantes']) || $data['qtd_visitantes'] < 1) {
        return "Informe pelo menos 1 visitante";
    }

    // proposito_visita
    if (empty($data['proposito_visita']) || strlen($data['proposito_visita']) < 5) {
        return "O propósito da visita deve ter no mínimo 5 caracteres";
    }

    // horários
if (empty($data['horario_entrada']) || empty($data['horario_saida'])) {
    return "Informe os horários de entrada e saída";
}

        // normaliza formato HH:MM
    $entrada = $data['horario_entrada'];
    $saida   = $data['horario_saida'];

    // valida formato básico
    if (!preg_match('/^\d{2}:\d{2}$/', $entrada) || !preg_match('/^\d{2}:\d{2}$/', $saida)) {
        return "Formato de horário inválido";
    }

    // converte para timestamp
    $entradaTime = strtotime($entrada);
    $saidaTime   = strtotime($saida);

    $min = strtotime("09:00");
    $max = strtotime("13:00");

    // intervalo permitido
    if ($entradaTime < $min || $entradaTime > $max) {
        return "Horário de entrada deve ser entre 09:00 e 13:00";
    }

    if ($saidaTime < $min || $saidaTime > $max) {
        return "Horário de saída deve ser entre 09:00 e 13:00";
    }

    // ordem lógica
    if ($saidaTime <= $entradaTime) {
    return "Horário de saída deve ser após o de entrada";
    }

    return null; // ✅ tudo certo
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
        $data = array_map('trim', $data);

         if ($erro = $this->validar($data)) {
        http_response_code(422);
        echo json_encode(["erro" => $erro]);
        return;
    }

        // validação

         $erros = $this->validar($data);

        if (!empty($erros)) {
        http_response_code(422);
        echo json_encode([
            "erro" => "Dados inválidos",
            "detalhes" => $erros
        ]);
        return;
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