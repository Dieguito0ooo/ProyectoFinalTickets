<?php
namespace App\Controllers;

use Illuminate\Database\Capsule\Manager as DB;

class TicketsController
{
    public function crearTicket($data, $user)
    {
        // Validar campos obligatorios que sí existen en tu tabla tickets
        if (!isset($data['titulo'], $data['descripcion'], $data['estado'], $data['gestor_id'])) {
            return ['error' => 'Faltan campos obligatorios'];
        }

        // Insertar usando Eloquent Capsule DB
        $ticketId = DB::table('tickets')->insertGetId([
            'titulo'        => $data['titulo'],
            'descripcion'   => $data['descripcion'],
            'estado'        => $data['estado'],
            'gestor_id'     => $data['gestor_id'],
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        return [
            'message' => 'Ticket creado correctamente',
            'ticket_id' => $ticketId
        ];
    }

    public function listarTickets()
    {
        $tickets = DB::table('tickets')->get();
        return $tickets;
    }
}
