<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Models\AuthToken;

class UsuariosController
{
    public function registerUser($data)
    {
        if (!isset($data['name'], $data['email'], $data['password'])) {
            return ['error' => 'Campos incompletos'];
        }

        if (Usuario::where('email', $data['email'])->exists()) {
            return ['error' => 'El correo ya está registrado'];
        }

        $user = Usuario::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => $data['role'] ?? 'gestor'
        ]);

        // generar token corto y guardarlo en auth_tokens
        $token = "token_{$user->role}_{$user->id}_" . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
        AuthToken::create([
            'user_id' => $user->id,
            'token'   => $token
        ]);

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function loginUser($data)
    {
        if (!isset($data['email'], $data['password'])) {
            return ['error' => 'Campos incompletos'];
        }

        $user = Usuario::where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user->password)) {
            return ['error' => 'Credenciales incorrectas'];
        }

        // generar token corto y guardarlo en auth_tokens
        $token = "token_{$user->role}_{$user->id}_" . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
        AuthToken::create([
            'user_id' => $user->id,
            'token'   => $token
        ]);

        return [
            'token' => $token,
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ];
    }

    public function getUsuarios()
    {
        $rows = Usuario::all(['id','name','email','role','created_at','updated_at']);
        if ($rows->isEmpty()) {
            return null;
        }
        return $rows->toJson();
    }
}
