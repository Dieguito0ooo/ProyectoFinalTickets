<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'prioridad',
        'estado',
        'usuario_id',
        'gestor_id'
    ];
}
