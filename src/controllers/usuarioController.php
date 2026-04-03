<?php

require_once __DIR__ . "/../config/jwt.php";
require_once __DIR__ . "/../models/UsuarioModel.php";

class UsuarioController {

    private $model;

    public function __construct($db){
        $this->model = new UsuarioModel($db);
    }

    public function login($data)
{
    $usuario = $this->model->login($data->email, $data->password);

    if (!$usuario) {
        echo json_encode([
            "success" => false,
            "message" => "Credenciales incorrectas"
        ]);
        exit;
    }

    $jwt = new JwtHandler();
    $token = $jwt->generateToken($usuario);

    echo json_encode([
        "success" => true,
        "token" => $token,
        "usuario" => [
            "id" => $usuario['id'],
            "email" => $usuario['email']
        ]
    ]);
}
}