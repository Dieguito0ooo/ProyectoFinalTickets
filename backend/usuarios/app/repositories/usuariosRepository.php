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
        $controller = new UsuariosController();
        $data = $request->getParsedBody();

        $result = $controller->registerUser($data);

        if (isset($result['error'])) {
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($result));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function login(Request $request, Response $response)
    {
        $controller = new UsuariosController();
        $data = $request->getParsedBody();

        $result = $controller->loginUser($data);

        if (isset($result['error'])) {
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function logout(Request $request, Response $response)
    {
        // Obtener token del header
        $authHeader = $request->getHeaderLine('Authorization');
        $token = trim(str_replace('Bearer', '', $authHeader));

        // Borrar token de la BD
        \Illuminate\Database\Capsule\Manager::table('auth_tokens')
            ->where('token', $token)
            ->delete();

        $response->getBody()->write(json_encode([
            "message" => "Sesión cerrada correctamente"
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

}
