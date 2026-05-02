<?php

require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../core/ConnectionManager.php';
require_once __DIR__ . '/../models/Agendamento.php';

class PagesController {
    
    /**
     * Renderiza a página inicial
     */
    public function home() {
        View::render('home');
    }

    /**
     * Renderiza a página de agendamento
     */
    public function agendamento() {
        View::render('agendamento');
    }

    /**
     * Renderiza o painel admin com lista de agendamentos
     */
    public function admin() {
        $conn = ConnectionManager::getConnection();
        $agendamentoModel = new Agendamento($conn);
        $agendamentos = $agendamentoModel->getAll();

        View::render('admin', [
            'title' => 'Painel Admin',
            'agendamentos' => $agendamentos
        ]);
    }

    /**
     * Renderiza página sobre
     */
    public function sobre() {
        View::render('sobre');
    }

    /**
     * Renderiza página contato
     */
    public function contato() {
        View::render('contato');
    }
}

?>
