<?php
require_once __DIR__ . "/../config/jwt.php";
require_once __DIR__ . "/../models/usuarioModel.php";
require_once __DIR__ . "/../models/mascotaModel.php";

class UsuarioController {
    private $model;
    private $db;

    public function __construct($db){
        $this->model = new UsuarioModel($db);
        $this->db = $db;
    }

    public function login($data){
        $usuario = $this->model->login($data->email);
        
        if (!$usuario || !password_verify($data->password, $usuario['password'])) {
            echo json_encode([
                "success" => false,
                "message" => "Credenciales incorrectas"
            ]);
            return;
        }

        $jwt = new JwtHandler();
        $token = $jwt->generateToken($usuario);
        echo json_encode([
            "success" => true,
            "token" => $token,
            "usuario" => [
                "id" => $usuario['id'],
                "email" => $usuario['email'],
                "rol" => $usuario['rol']
            ]
        ]);
    }

    public function register($data){
        if ($this->model->emailExists($data->email)) {
            echo json_encode([
                "success" => false,
                "message" => "El email ya está registrado"
            ]);
            return;
        }

        $nuevoId = $this->model->register(
            $data->nombre,
            $data->email,
            $data->password
        );

        if (!$nuevoId){
            echo json_encode([
                "success" => false,
                "message" => "Error al registrar el usuario"
            ]);
            return;
        }

        if (isset($data->mascota)){
            $mascotaModel = new Mascota($this->db);
            $mascotaModel->createMascota(
                $data->mascota->nombre,
                $data->mascota->raza,
                $data->mascota->edad,
                $data->mascota->observaciones,
                $nuevoId
            );
        }
        echo json_encode([
            "success" => true,
            "message" => "Usuario registrado correctamente"
        ]);
    }
}