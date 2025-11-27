<?php
namespace App\Repositories;

use PDO;

class AuthTokenRepository
{
    private $db;

    public function __construct()
    {
        require __DIR__ . '/../config/database.php';
        $this->db = $pdo;
    }

    public function getUserByToken(string $token)
    {
        $stmt = $this->db->prepare("
            SELECT users.id, users.name, users.email, users.role
            FROM auth_tokens
            INNER JOIN users ON users.id = auth_tokens.user_id
            WHERE auth_tokens.token = ? LIMIT 1
        ");
        $stmt->execute([$token]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
