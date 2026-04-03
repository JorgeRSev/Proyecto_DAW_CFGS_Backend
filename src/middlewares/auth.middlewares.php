<?php

require_once __DIR__ . "/../config/jwt.php";

class JwtMiddleware {

    public static function check()
    {
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
        $token = str_replace("Bearer ", "", $authHeader);

        if (!$token) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Token inválido"
            ]);
            exit;
        }

        try {
            $jwt = new JwtHandler();
            $decoded = $jwt->validateToken($token);
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
}