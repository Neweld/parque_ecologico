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

    public function dispatch() {

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/parque_ecologico/public', '', $uri);
        $uri = rtrim($uri, '/') ?: '/';

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {

            if ($route['method'] !== $method) continue;

            // transforma /agendamentos/{id} em regex
            $pattern = preg_replace('#\{[a-zA-Z]+\}#', '(\d+)', $route['path']);
            $pattern = "#^" . rtrim($pattern, '/') . "$#";

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

        require_once "../app/controllers/$controllerName.php";

        $controller = new $controllerName();

        return call_user_func_array([$controller, $method], $params);
    }
}





?>