<?php

require_once __DIR__ . "/../libs/jwt/autoload.php";
require_once __DIR__ . '/../config/jwt.php';

use Firebase\JWT\JWT;

class AuthMiddleware {

    public static function verifyToken() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Token no enviado"
            ]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        $token      = str_replace("Bearer ", "", $authHeader);

        try {
            $secret  = getenv('JWT_SECRET') ?: 'pelupatas_super_secret_key_2026';
            $decoded = JWT::decode($token, $secret);
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

    public static function requireRol(array $rolesPermitidos) {
        $user = self::verifyToken();

        if (!in_array($user->rol, $rolesPermitidos)) {
            http_response_code(403);
            echo json_encode([
                "success" => false,
                "message" => "Acceso denegado: rol no permitido"
            ]);
            exit;
        }
        return $user;
    }
}