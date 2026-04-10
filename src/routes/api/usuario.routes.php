<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/usuarioController.php";

$database = new Database();
$db = $database->getConnection();
$controller = new UsuarioController($db);

$method = $_SERVER['REQUEST_METHOD'];

function getInputData() {
    return json_decode(file_get_contents("php://input"));
}

    if ($method === "POST") {
        $data = getInputData();
        if (!$data) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "JSON inválido"]);
            exit;
        }
        if (isset($data->email, $data->password) && !isset($data->nombre)) {
            $controller->login($data);
        }
        else if (isset($data->nombre, $data->email, $data->password)) {
            $controller->register($data);
        }
        else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Datos incompletos"
            ]);
        }
    } else {
        http_response_code(405);
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido"
        ]);
    }