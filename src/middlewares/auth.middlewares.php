<?php
require_once __DIR__ . "/../libs/jwt/src/JWT.php";
require_once __DIR__ . "/../libs/jwt/src/Key.php";
require_once __DIR__ . '/../config/jwt.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

    public static function verifyToken() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])){
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Token no enviado"
            ]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token = str_replace("Bearer ", "", $authHeader);

        try {
            $secret = "pelupatas_super_secret_key_2026";
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return $decoded->data;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Token inválido o expirado"
            ]);
            exit;
        }
    }

    function getUserFromToken() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])){
            return null;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = decodeJWT($token);
            return $decoded->user_id ?? null;
        } catch (Exception $e) {
            return null;
        }
    }
}

