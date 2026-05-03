<?php

class Agendamento {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {

        $sql = "INSERT INTO agendamento (
            nome_instituicao,
            nome_diretor,
            nome_responsavel,
            email,
            data_reserva,
            
            faixa_etaria,
            qtd_visitantes,
            proposito_visita,
            usa_grama,
            usa_quiosque,
            horario_entrada,
            horario_saida
        ) VALUES (
            :nome_instituicao,
            :nome_diretor,
            :nome_responsavel,
            :email,
            :data_reserva,
            
            :faixa_etaria,
            :qtd_visitantes,
            :proposito_visita,
            :usa_grama,
            :usa_quiosque,
            :horario_entrada,
            :horario_saida
        )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":nome_instituicao" => $data['nome_instituicao'] ?? null,
            ":nome_diretor" => $data['nome_diretor'] ?? null,
            ":nome_responsavel" => $data['nome_responsavel'],
            ":email" => $data['email'],
            ":data_reserva" => $data['data_reserva'],
            
            ":faixa_etaria" => $data['faixa_etaria'],
            ":qtd_visitantes" => $data['qtd_visitantes'],
            ":proposito_visita" => $data['proposito_visita'],
            ":usa_grama" => $data['usa_grama'] ?? 0,
            ":usa_quiosque" => $data['usa_quiosque'] ?? 0,
            ":horario_entrada" => $data['horario_entrada'],
            ":horario_saida" => $data['horario_saida'],
        ]);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM agendamento ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>