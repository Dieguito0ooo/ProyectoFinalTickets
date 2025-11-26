<?php
namespace App\Repositories;

use App\Controllers\UsuariosController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


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

    public function login(Request $request, Response $response)
{
    $controller = new UsuariosController();
    $data = $request->getParsedBody();

    $jwtConfig = require __DIR__ . '/../config/jwt.php';
    

    $user = $controller->loginUser($data);

    if (isset($user['error'])) {
        $response->getBody()->write(json_encode($user));
        return $response->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
    }

    // Crear payload del JWT
    $payload = [
        'iss' => $jwtConfig['issuer'],
        'aud' => $jwtConfig['audience'],
        'iat' => time(),
        'exp' => time() + $jwtConfig['expiration'],
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role
        ]
    ];

    $token = JWT::encode($payload, $jwtConfig['secret_key'], 'HS256');

    $response->getBody()->write(json_encode([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]
    ]));

    return $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
}


}
