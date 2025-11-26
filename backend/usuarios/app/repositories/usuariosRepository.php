<?php
namespace App\Repositories;

use App\Controllers\UsuariosController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsuariosRepository
{
    public function queryAllUsuarios(Request $request, Response $response)
    {
        $controller = new UsuariosController();
        $data = $controller->getUsuarios();

        if ($data === null) {
            return $response->withStatus(204);
        }

        $response->getBody()->write($data);
        return $response->withHeader('Content-Type','application/json');
    }

    public function register(Request $request, Response $response)
    {
          error_log("METODO RECIBIDO: " . $request->getMethod());

        $controller = new UsuariosController();
        $data = $request->getParsedBody();

        $result = $controller->registerUser($data);

        if (isset($result['error'])) {
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(400)
                            ->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus(201)
                        ->withHeader('Content-Type', 'application/json');
    }

}
