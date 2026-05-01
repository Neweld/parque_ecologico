<?php

class Router {
    private $routes = [];

    public function get($path, $action) {
        $this->addRoute('GET', $path, $action);
    }

    public function post($path, $action) {
        $this->addRoute('POST', $path, $action);
    }

    public function put($path, $action) {
        $this->addRoute('PUT', $path, $action);
    }

    public function delete($path, $action) {
        $this->addRoute('DELETE', $path, $action);
    }

    private function addRoute($method, $path, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->normalizeRoutePath($path),
            'action' => $action
        ];
    }

    public static function route() {
        $router = new self();

        $router->get('/', 'PagesController@home');
        $router->get('/agendamento', 'PagesController@agendamento');
        $router->get('/admin', 'PagesController@admin');
        $router->get('/sobre', 'PagesController@sobre');
        $router->get('/contato', 'PagesController@contato');

        $router->get('/api/agendamentos', 'AgendamentoController@index');
        $router->post('/api/agendamentos', 'AgendamentoController@store');
        $router->put('/api/agendamentos/aprovar/{id}', 'AgendamentoController@aprovar');
        $router->put('/api/agendamentos/rejeitar/{id}', 'AgendamentoController@rejeitar');
        $router->delete('/api/agendamentos/excluir/{id}', 'AgendamentoController@delete');

        $router->dispatch();
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = $this->normalizeRequestUri($uri);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '(\d+)', $route['path']);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';

            if ($route['path'] === '/') {
                $pattern = '#^/$#';
            }

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->callAction($route['action'], $matches);
            }
        }

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            "erro" => "Rota nao encontrada",
            "uri_recebida" => $uri
        ]);
    }

    private function callAction($action, $params) {
        list($controllerName, $method) = explode('@', $action);

        require_once __DIR__ . "/../controllers/$controllerName.php";

        $controller = new $controllerName();

        return call_user_func_array([$controller, $method], $params);
    }

    private function normalizeRequestUri($uri) {
        $uri = str_replace('/parque_ecologico/public', '', $uri);
        $uri = str_replace('/parque_ecologico', '', $uri);

        return $this->normalizeRoutePath($uri);
    }

    private function normalizeRoutePath($path) {
        $path = '/' . trim($path, '/');
        return rtrim($path, '/') ?: '/';
    }
}

?>
