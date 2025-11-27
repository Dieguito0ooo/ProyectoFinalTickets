<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RoleMiddleware implements MiddlewareInterface
{
    private $roleRequired;

    public function __construct(string $role)
    {
        $this->roleRequired = $role;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if (!$user || $user['role'] !== $this->roleRequired) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                "error" => "Acceso denegado",
                "detail" => "Se requiere rol: {$this->roleRequired}"
            ]));

            return $response->withStatus(403)
                            ->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
