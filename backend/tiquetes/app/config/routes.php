<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\AuthMiddleware;
use App\Repositories\TicketsRepository;

return function (App $app) {

    // Ruta de prueba
    $app->get('/', function ($req, $res) {
        $res->getBody()->write("Microservicio Tiquetes OK");
        return $res;
    });

    // Grupo de rutas /tickets
    $app->group('/tickets', function (RouteCollectorProxy $group) {

        // Crear un ticket (protegido)
        $group->post('/crear', [TicketsRepository::class, 'crearTicket'])
              ->add(new AuthMiddleware());

        // Listar todos los tickets (protegido)
        $group->get('/all', [TicketsRepository::class, 'listarTickets'])
              ->add(new AuthMiddleware());

    });

};
