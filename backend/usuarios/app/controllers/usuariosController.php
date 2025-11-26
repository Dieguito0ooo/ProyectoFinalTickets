<?php
namespace App\Controllers;

use App\Models\Usuario;

class UsuariosController
{
    public function getUsuarios()
    {
        $rows = Usuario::all();
        if ($rows->isEmpty()) {
            return null;
        }
        return $rows->toJson();
    }

    public function registerUser($data)
    {
        // Validaciones simples
        if (!isset($data['name'], $data['email'], $data['password'])) {
            return ['error' => 'Campos incompletos'];
        }

        // Validar que el correo no exista
        if (Usuario::where('email', $data['email'])->exists()) {
            return ['error' => 'El correo ya está registrado'];
        }

        // Crear usuario
        $user = Usuario::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'     => 'gestor'
        ]);

        return $user->toArray();
    }

    public function loginUser($data)
    {
        if (!isset($data['email'], $data['password'])) {
            return ['error' => 'Campos incompletos'];
        }

        $user = Usuario::where('email', $data['email'])->first();

        if (!$user) {
            return ['error' => 'Usuario no encontrado'];
        }

        if (!password_verify($data['password'], $user->password)) {
            return ['error' => 'Contraseña incorrecta'];
        }

        return $user;
    }

}
