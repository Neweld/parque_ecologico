<?php

require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../../config/database.php';
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
    public function login(){
        View::render('login');
    }

    /**
     * Renderiza o painel admin com lista de agendamentos
     */
    public function admin() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['admin_logado'])) {
            header('Location: /parque_ecologico/login');
            exit;
        }

        $conn = Database::connect();
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

    public function quiz() {
        View::render('quiz', [
            'title' => 'Quiz Ecológico'
        ]);
    }

    public function jogo() {
        View::render('jogo', [
            'title' => 'Caça-palavras Ecológico'
        ]);
    }
}

?>
