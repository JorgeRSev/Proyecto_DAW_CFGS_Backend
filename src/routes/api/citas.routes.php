<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/CitaController.php";
require_once __DIR__ . "/../../middleware/authMiddleware.php";

$user = JwtMiddleware::check();

$database = new Database();
$db = $database->getConnection();
$controller = new CitaController($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

function getInputData() {
    $data = json_decode(file_get_contents("php://input"));
    return $data ?: null;
}

switch ($method) {
    case "GET":
        $id ? $controller->getById($id) : $controller->getAll();
        break;

    case "POST":
        $data = getInputData();
        if ($data && isset($data->fecha, $data->hora, $data->id_mascota, $data->id_peluquera)) {
            $controller->create($data);
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
        }
        break;

    case "PUT":
        if ($id) {
            $data = getInputData();
            if ($data && isset($data->fecha, $data->hora, $data->id_mascota, $data->id_peluquera)) {
                $controller->update($id, $data);
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Faltan datos para actualizar"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Falta ID"]);
        }
        break;

    case "DELETE":
        if ($id) {
            $controller->delete($id);
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Falta ID"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
}