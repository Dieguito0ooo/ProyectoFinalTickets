<?php
use App\Repositories\UsuariosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', function ($request, $response) {
        $response->getBody()->write("Hello usuarios!");
        return $response;
    });

  $app->group('/usuarios', function (RouteCollectorProxy $group) {

    // Rutas públicas
    $group->get('/all', [UsuariosRepository::class, 'queryAllUsuarios']);
    $group->post('/register', [UsuariosRepository::class, 'register']);
    $group->post('/login', [UsuariosRepository::class, 'login']);

    // Ruta protegida
    $group->get('/me', function ($request, $response) {
        $user = $request->getAttribute('user');
        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    })->add(new \App\Middleware\AuthMiddleware());

    // Cerrar sesión (logout)
    $group->post('/logout', [UsuariosRepository::class, 'logout'])
      ->add(new \App\Middleware\AuthMiddleware());

    // Listar usuarios solo si es admin
    $group->get('/admin/list', [UsuariosRepository::class, 'queryAllUsuarios'])
      ->add(new \App\Middleware\RoleMiddleware('admin'))
      ->add(new \App\Middleware\AuthMiddleware());



});


    
};

