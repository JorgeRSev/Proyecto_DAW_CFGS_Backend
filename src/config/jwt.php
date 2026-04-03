<?php

require_once __DIR__ . "/../libs/jwt/JWT.php";

use Firebase\JWT\JWT;

class JwtHandler {

    private $secret = "CLAVE_SUPER_SIMPLE_123";

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
        return JWT::decode($token, new \Firebase\JWT\Key($this->secret, 'HS256'));
    }
}