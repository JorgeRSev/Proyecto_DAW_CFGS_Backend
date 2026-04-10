<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/citaController.php";
require_once __DIR__ . "/../../middlewares/auth.middlewares.php";

$user = AuthMiddleware::verifyToken();

$database = new Database();
$db = $database->getConnection();
$controller = new CitaController($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

$data = json_decode(file_get_contents("php://input"));

if ($method === "GET") {
    isset($_GET['mine'])
        ? $controller->getMisCitas($user->id)
        : $controller->getAll();
} 
elseif ($method === "POST") {
    if ($data && isset($data->fecha, $data->hora, $data->id_mascota, $data->id_peluquera)) {
        $controller->create($data);
    }
} 
elseif ($method === "PUT") {
    if ($id && $data) {
        $controller->update($id, $data);
    }
} 
elseif ($method === "DELETE") {
    if ($id) {
        $controller->delete($id);
    }
}