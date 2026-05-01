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
            'path' => $path,
            'action' => $action
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

                return $this->callAction($route['action'], $matches);
            }
        }

        http_response_code(404);
        echo json_encode(["erro" => "Rota não encontrada", "uri" => $uri]);
    }

    private function callAction($action, $params) {

        list($controllerName, $method) = explode('@', $action);

        require_once "../app/controllers/$controllerName.php";

        $controller = new $controllerName();

        return call_user_func_array([$controller, $method], $params);
    }
}





?>