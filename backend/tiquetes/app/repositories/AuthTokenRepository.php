<?php
namespace App\Repositories;

use Illuminate\Database\Capsule\Manager as DB;

class AuthTokenRepository
{
    public function getUserByToken($token)
    {
        $row = DB::table('auth_tokens')
            ->where('token', $token)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$row) {
            return null;
        }

        $user = DB::table('users')->where('id', $row->user_id)->first();

        return $user ?: null;
    }
}
