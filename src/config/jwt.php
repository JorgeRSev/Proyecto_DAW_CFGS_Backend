<?php

require_once __DIR__ . "/../libs/jwt/JWT.php";

use Firebase\JWT\JWT;

class JwtHandler {

    private $secret = "pelupatas_super_secret_key_2026";

    public function generateToken($user)
    {
        $payload = [
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "id" => $user['id'],
                "email" => $user['email']
            ]
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validate($token)
{
    try {
        return JWT::decode($token, new \Firebase\JWT\Key($this->secret, 'HS256'));
    } catch (Exception $e) {
        return false;
    }
}
}