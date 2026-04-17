<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../controllers/citaController.php";
require_once __DIR__ . "/../../middlewares/auth.middlewares.php";

$database   = new Database();
$db         = $database->getConnection();
$controller = new CitaController($db);

$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;
$data   = json_decode(file_get_contents("php://input"));

if ($method === "GET") {
    if (isset($_GET['mine'])) {
        $user = AuthMiddleware::verifyToken();
        $controller->getMisCitas($user->id);
    } else {
        $user = AuthMiddleware::requireRol(['peluquera', 'admin']);
        $controller->getAll();
    }

} elseif ($method === "POST") {
    $user = AuthMiddleware::verifyToken();

    if (!$data || !isset($data->fecha, $data->hora, $data->id_mascota, $data->id_peluquera)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Faltan campos obligatorios"
        ]);
        exit;
    }

    $controller->create($data);

} elseif ($method === "PUT") {
    $user = AuthMiddleware::requireRol(['peluquera', 'admin']);
    if (!$id) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta el id de la cita en la URL"
        ]);
        exit;
    }

    if (!$data || !isset($data->estado)) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta el campo 'estado' en el cuerpo de la petición"
        ]);
        exit;
    }

    $controller->updateEstado($id, $data);

} elseif ($method === "DELETE") {

    $user = AuthMiddleware::requireRol(['peluquera', 'admin']);

    if (!$id) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Falta el id de la cita en la URL"
        ]);
        exit;
    }

    $controller->delete($id);

} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido"
    ]);
}