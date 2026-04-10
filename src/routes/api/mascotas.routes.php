<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/mascotaController.php";
require_once __DIR__ . "/../../middlewares/auth.middlewares.php";

$user = AuthMiddleware::verifyToken();

$database = new Database();
$db = $database->getConnection();
$controller = new MascotaController($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

function getInputData() {
    return json_decode(file_get_contents("php://input"));
}

    if ($method === "GET") {
        $id ? $controller->getById($id) : $controller->getAll($user->id);
    } 
    elseif ($method === "POST") {
        $data = getInputData();
        if ($data && isset($data->nombre, $data->raza, $data->edad)) {
            $data->id_dueno = $user->id;
            $controller->create($data);
        }
    } 
    elseif ($method === "PUT") {
        $data = getInputData();
        if ($id && $data) {
            $controller->update($id, $data, $user->id);
        }
    } 
    elseif ($method === "DELETE") {
        if ($id) {
            $controller->delete($id, $user->id);
        }
    }