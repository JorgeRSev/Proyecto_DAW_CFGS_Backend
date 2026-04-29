<?php
require_once __DIR__ . "/../libs/jwt/autoload.php";

use Firebase\JWT\JWT;

class JwtHandler
{

    private string $secret;

    public function __construct()
    {
        $this->secret = getenv('JWT_SECRET') ?: 'pelupatas_super_secret_key_2026';
    }

    public function generateToken(array $user): string
    {
        $payload = [
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "rol" => $user['rol']
            ]
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validate(string $token): mixed
    {
        try {
            return JWT::decode($token, $this->secret);
        } catch (Exception $e) {
            return false;
        }
    }
}