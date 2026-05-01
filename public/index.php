    <?php

        header("Content-Type: application/json");

        require_once '../app/core/Router.php';

        $router = new Router();


        $router->get('/agendamentos', 'AgendamentoController@index');
        $router->post('/agendamentos', 'AgendamentoController@store');
        $router->put('/agendamentos/aprovar/{id}', 'AgendamentoController@aprovar');
        $router->put('/agendamentos/rejeitar/{id}', 'AgendamentoController@rejeitar');
        $router->delete('/agendamentos/{id}', 'AgendamentoController@delete');


        $router->dispatch();
    
    
    ?>