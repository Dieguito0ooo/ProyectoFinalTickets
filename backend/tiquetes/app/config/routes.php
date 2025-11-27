<?php

use Slim\App;

return function (App $app) {

    $app->get('/', function ($request, $response) {
        $response->getBody()->write("Microservicio de Tiquetes OK");
        return $response;
    });

};
