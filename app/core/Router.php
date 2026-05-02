<?php

class Router {
    private $routes = [];

    public function get($path, $action, $middleware = null) {
    $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post($path, $action, $middleware = null) {
    $this->addRoute('POST', $path, $action, $middleware);
    }

    public function put($path, $action, $middleware = null) {
    $this->addRoute('PUT', $path, $action, $middleware);
    }

    public function delete($path, $action, $middleware = null) {
    $this->addRoute('DELETE', $path, $action, $middleware);
    }

    private function addRoute($method, $path, $action, $middleware = null) {
    $this->routes[] = [
        'method' => $method,
        'path' => $path,
        'action' => $action,
        'middleware' => $middleware
    ];
}

    public static function route() {
        $router = new self();

        $router->get('/', 'PagesController@home');
        $router->get('/agendamento', 'PagesController@agendamento');
        $router->get('/admin', 'PagesController@admin');
        $router->get('/sobre', 'PagesController@sobre');
        $router->get('/contato', 'PagesController@contato');

         //rotas públicas
        $router->post('/login', 'AuthController@login');
        $router->post('/logout', 'AuthController@logout');
        $router->get('/check-auth', 'AuthController@check');

        
        

        //form público para agendamento
        $router->post('/api/agendamentos/enviar', 'AgendamentoController@store');

        //rotas de admin
        $router->get('/api/agendamentos/listar', 'AgendamentoController@index', 'AuthMiddleware');
        $router->put('api//agendamentos/aprovar/{id}', 'AgendamentoController@aprovar', 'AuthMiddleware');
        $router->put('api/agendamentos/rejeitar/{id}', 'AgendamentoController@rejeitar', 'AuthMiddleware');
        $router->delete('api/agendamentos/excluir/{id}', 'AgendamentoController@delete', 'AuthMiddleware');


        

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

                array_shift($matches); // remove match completo
                if ($route['middleware']) {
                require_once "../app/middlewares/{$route['middleware']}.php";
                $route['middleware']::handle();
                }




                return $this->callAction($route['action'], $matches);
            }
        }

        http_response_code(404);
        echo json_encode(["erro" => "Rota não encontrada", "uri: " => $uri]);
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
