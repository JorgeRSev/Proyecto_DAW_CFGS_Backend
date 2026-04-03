<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/UsuarioController.php";


header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        echo json_encode([
            "success" => false,
            "message" => "Body vacío"
        ]);
        exit;
    }

    $controller->login($data);
    exit;
}

http_response_code(405);
echo json_encode(["message" => "Método no permitido"]);