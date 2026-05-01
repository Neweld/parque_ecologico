    <?php

        header("Content-Type: application/json");

        require_once '../app/core/Router.php';

        $router = new Router();



        //rotas públicas
        $router->post('/login', 'AuthController@login');
        $router->post('/logout', 'AuthController@logout');
        $router->get('/check-auth', 'AuthController@check');

        
        

        //form público para agendamento
        $router->post('/agendamentos', 'AgendamentoController@store');

        //rotas de admin
        $router->get('/agendamentos', 'AgendamentoController@index', 'AuthMiddleware');
        $router->put('/agendamentos/aprovar/{id}', 'AgendamentoController@aprovar', 'AuthMiddleware');
        $router->put('/agendamentos/rejeitar/{id}', 'AgendamentoController@rejeitar', 'AuthMiddleware');
        $router->delete('/agendamentos/excluir/{id}', 'AgendamentoController@delete', 'AuthMiddleware');


        $router->dispatch();
    
    
    ?>