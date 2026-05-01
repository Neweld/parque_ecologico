<?php

class Router {
    public static function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        require_once __DIR__ . '/../controllers/PagesController.php';
        $pagesController = new PagesController();

        if ($uri === '/parque_ecologico' || $uri === '') {
            $pagesController->home();
            return;
        }

        if ($uri === '/parque_ecologico/agendamento' && $method === 'GET') {
            $pagesController->agendamento();
            return;
        }

        if ($uri === '/parque_ecologico/admin' && $method === 'GET') {
            $pagesController->admin();
            return;
        }

        if ($uri === '/parque_ecologico/sobre' && $method === 'GET') {
            $pagesController->sobre();
            return;
        }

        if ($uri === '/parque_ecologico/contato' && $method === 'GET') {
            $pagesController->contato();
            return;
        }

        require_once __DIR__ . '/../controllers/AgendamentoController.php';
        $agendamentoController = new AgendamentoController();

        if ($uri === '/parque_ecologico/api/agendamentos' && $method === 'GET') {
            $agendamentoController->index();
            return;
        }

        if ($uri === '/parque_ecologico/api/agendamentos' && $method === 'POST') {
            $agendamentoController->store();
            return;
        }

        if (preg_match('#^/parque_ecologico/api/agendamentos/aprovar/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $agendamentoController->aprovar($matches[1]);
            return;
        }

        if (preg_match('#^/parque_ecologico/api/agendamentos/rejeitar/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $agendamentoController->rejeitar($matches[1]);
            return;
        }

        if (preg_match('#^/parque_ecologico/api/agendamentos/excluir/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
            $agendamentoController->delete($matches[1]);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "erro" => "Rota não encontrada",
            "uri_recebida" => $uri
        ]);
    }
}

?>
