<?php

class Router {
    public static function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // ============================================
        // ROTAS DE PÁGINAS HTML
        // ============================================
        
        require_once __DIR__ . '/../controllers/PagesController.php';
        $pagesController = new PagesController();

        // Rota: /parque_ecologico ou /parque_ecologico/ (home)
        if ($uri === '/parque_ecologico' || $uri === '') {
            $pagesController->home();
            return;
        }

        // Rota: /parque_ecologico/agendamento
        if ($uri === '/parque_ecologico/agendamento' && $method === 'GET') {
            $pagesController->agendamento();
            return;
        }

        // Rota: /parque_ecologico/admin
        if ($uri === '/parque_ecologico/admin' && $method === 'GET') {
            $pagesController->admin();
            return;
        }

        // Rota: /parque_ecologico/sobre
        if ($uri === '/parque_ecologico/sobre' && $method === 'GET') {
            $pagesController->sobre();
            return;
        }

        // Rota: /parque_ecologico/contato
        if ($uri === '/parque_ecologico/contato' && $method === 'GET') {
            $pagesController->contato();
            return;
        }

        // ============================================
        // ROTAS DE API (JSON)
        // ============================================

        require_once __DIR__ . '/../controllers/AgendamentoController.php';
        $agendamentoController = new AgendamentoController();

        // Rota: GET /parque_ecologico/api/agendamentos
        if ($uri === '/parque_ecologico/api/agendamentos' && $method === 'GET') {
            $agendamentoController->index();
            return;
        }

        // Rota: POST /parque_ecologico/api/agendamentos
        if ($uri === '/parque_ecologico/api/agendamentos' && $method === 'POST') {
            $agendamentoController->store();
            return;
        }

        // Rota: PUT /parque_ecologico/api/agendamentos/aprovar/ID
        if (preg_match('#^/parque_ecologico/api/agendamentos/aprovar/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $agendamentoController->aprovar($matches[1]);
            return;
        }

        // Rota: PUT /parque_ecologico/api/agendamentos/rejeitar/ID
        if (preg_match('#^/parque_ecologico/api/agendamentos/rejeitar/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            $agendamentoController->rejeitar($matches[1]);
            return;
        }

        // Rota: DELETE /parque_ecologico/api/agendamentos/excluir/ID
        if (preg_match('#^/parque_ecologico/api/agendamentos/excluir/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
            $agendamentoController->delete($matches[1]);
            return;
        }

        // ============================================
        // 404 - Rota não encontrada
        // ============================================

        http_response_code(404);
        echo json_encode([
            "erro" => "Rota não encontrada",
            "uri_recebida" => $uri
        ]);
    }
}

?>
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