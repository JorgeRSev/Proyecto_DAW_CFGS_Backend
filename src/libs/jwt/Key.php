<?php

namespace Firebase\JWT;

class JWT {

    public static function encode($payload, $key, $alg = 'HS256') {
        $header = json_encode(['typ' => 'JWT', 'alg' => $alg]);

        $base64Header = self::base64UrlEncode($header);
        $base64Payload = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $key, true);
        $base64Signature = self::base64UrlEncode($signature);

        return "$base64Header.$base64Payload.$base64Signature";
    }

    public static function decode($jwt, $key) {
        $parts = explode('.', $jwt);

        if (count($parts) != 3) {
            throw new \Exception("Token inválido");
        }

        [$header, $payload, $signature] = $parts;

        $valid = hash_hmac('sha256', "$header.$payload", $key, true);
        $valid = self::base64UrlEncode($valid);

        if ($valid !== $signature) {
            throw new \Exception("Firma inválida");
        }

        return json_decode(self::base64UrlDecode($payload));
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}