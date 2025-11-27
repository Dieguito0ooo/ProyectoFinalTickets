<?php
namespace App\Repositories;

use Illuminate\Database\Capsule\Manager as DB;

class AuthTokenRepository
{
    public function getUserByToken(string $token)
    {
        // Buscar el último token registrado en la tabla logins
        $row = DB::table('logins')
            ->where('token', $token)
            ->orderBy('timestamp_login', 'desc')
            ->first();

        if (!$row) {
            return null;
        }

        // Buscar el usuario asociado
        $user = DB::table('users')
            ->where('id', $row->user_id)
            ->first();

        if (!$user) {
            return null;
        }

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role
        ];
    }
}
