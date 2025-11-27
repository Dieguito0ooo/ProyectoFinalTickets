<?php
namespace App\Repositories;

use App\Controllers\TicketsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketsRepository
{
    public function crearTicket(Request $request, Response $response)
    {
        $controller = new TicketsController();

        $data = $request->getParsedBody();
        $user = $request->getAttribute('user'); // viene del token

        $resultado = $controller->crearTicket($data, $user);

        if (isset($resultado['error'])) {
            $response->getBody()->write(json_encode($resultado));
            return $response
                ->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($resultado));
        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

    public function listarTickets(Request $request, Response $response)
    {
        $controller = new TicketsController();
        $resultado = $controller->listarTickets();

        $response->getBody()->write(json_encode($resultado));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
