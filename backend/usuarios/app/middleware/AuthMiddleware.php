<?php
namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Token no enviado']));
            return $response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $jwtConfig = require __DIR__ . '/../config/jwt.php';

            $decoded = JWT::decode($token, new Key($jwtConfig['secret_key'], 'HS256'));

            // Guardar usuario dentro del request
            $request = $request->withAttribute('user', (array) $decoded->user);

            // Continuar al siguiente middleware o ruta
            return $handler->handle($request);

        } catch (\Exception $e) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'error' => 'Token inválido',
                'detail' => $e->getMessage()
            ]));

            return $response->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
        }
    }
}
