<?php
header("Content-Type: application/json");

require_once "../config/database.php";
require_once "../models/usuarioModel.php";

session_start();

$database = new Database();
$db = $database->getConnection();
$usuarioModel = new Usuario($db);
$data = json_decode(file_get_contents("php://input"));

if($data && isset($data->email) && isset($data->password)){
    $usuario = $usuarioModel->login($data->email, $data->password);
    if($usuario){
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];
        echo json_encode([
            "success" => true,
            "rol" => $usuario['rol']
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Credenciales incorrectas"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
}