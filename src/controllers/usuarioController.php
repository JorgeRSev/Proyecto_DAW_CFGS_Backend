<?php
require_once __DIR__ . "/../config/jwt.php";
require_once __DIR__ . "/../models/usuarioModel.php";
require_once __DIR__ . "/../models/mascotaModel.php";

class UsuarioController
{
    private $model;
    private $db;

    public function __construct($db)
    {
        $this->model = new UsuarioModel($db);
        $this->db = $db;
    }

    public function login($data)
    {
        $usuario = $this->model->login($data->email);

        if (!$usuario || !password_verify($data->password, $usuario['password'])) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
            return;
        }

        $jwt = new JwtHandler();
        $token = $jwt->generateToken($usuario);

        echo json_encode([
            "success" => true,
            "token" => $token,
            "usuario" => [
                "id" => $usuario['id'],
                "nombre" => $usuario['nombre'],
                "email" => $usuario['email'],
                "telefono" => $usuario['telefono'] ?? null,
                "rol" => $usuario['rol']
            ]
        ]);
    }

    public function register($data)
    {
        if ($this->model->emailExists($data->email)) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "El email ya está registrado"]);
            return;
        }

        $nuevoId = $this->model->register($data->nombre, $data->email, $data->password);

        if (!$nuevoId) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al registrar el usuario"]);
            return;
        }

        if (isset($data->mascota)) {
            $mascotaModel = new Mascota($this->db);
            $mascotaModel->createMascota(
                $data->mascota->nombre,
                $data->mascota->raza,
                $data->mascota->edad,
                $data->mascota->observaciones,
                $nuevoId
            );
        }
        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
    }

    public function getPerfil($userId)
    {
        $perfil = $this->model->getPerfil($userId);
        if (!$perfil) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
            return;
        }
        echo json_encode(["success" => true, "data" => $perfil]);
    }

    public function actualizarPerfil($userId, $data)
    {
        if (!isset($data->nombre) || !isset($data->email)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Nombre y email son obligatorios"]);
            return;
        }

        if ($this->model->emailExists($data->email, $userId)) {
            http_response_code(409);
            echo json_encode(["success" => false, "message" => "El email ya está en uso por otro usuario"]);
            return;
        }

        $telefono = $data->telefono ?? null;

        $ok = $this->model->actualizarPerfil($userId, $data->nombre, $data->email, $telefono);

        if (!$ok) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al actualizar el perfil"]);
            return;
        }

        $perfilActualizado = $this->model->getPerfil($userId);
        echo json_encode([
            "success" => true,
            "message" => "Perfil actualizado correctamente",
            "usuario" => $perfilActualizado
        ]);
    }

    public function cambiarPassword($userId, $data)
    {
        if (!isset($data->password_actual, $data->password_nueva)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Faltan campos"]);
            return;
        }

        $hashActual = $this->model->getPasswordById($userId);
        if (!password_verify($data->password_actual, $hashActual)) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "La contraseña actual no es correcta"]);
            return;
        }

        $ok = $this->model->cambiarPassword($userId, $data->password_nueva);
        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Contraseña actualizada correctamente" : "Error al actualizar"
        ]);
    }
}