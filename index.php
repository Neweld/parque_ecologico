<?php
/**
 * Index - Router Principal do Parque Ecológico
 * 
 * Arquivo de entrada único para toda a aplicação
 * Roteia requisições para controllers apropriados (páginas ou API)
 */



header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, X-CSRF-Token");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once './app/core/Router.php';

Router::route();

?>
