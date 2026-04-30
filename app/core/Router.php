<?php

    class Router {
    public static function route() {
         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        $method = $_SERVER['REQUEST_METHOD'];

        require_once '../app/controllers/AgendamentoController.php';
        $controller = new AgendamentoController();

        
        if ($uri === '/parque_ecologico/public/agendamentos' && $method === 'GET') {
            $controller->index();
            return;
        }

        
        if ($uri === '/parque_ecologico/public/agendamentos' && $method === 'POST') {
            $controller->store();
            return;
        }

        
        if (preg_match('#^/parque_ecologico/public/agendamentos/aprovar/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $controller->aprovar($matches[1]);
            return;
        }

        
        if (preg_match('#^/parque_ecologico/public/agendamentos/rejeitar/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $controller->rejeitar($matches[1]);
            return;
        }

       
        if (preg_match('#^/parque_ecologico/public/agendamentos/excluir/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
            $controller->delete($matches[1]);
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