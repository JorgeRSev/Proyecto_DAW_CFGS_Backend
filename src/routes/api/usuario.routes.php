<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../models/usuarioModel.php";
require_once __DIR__ . "/../../middlewares/auth.middlewares.php";

$database = new Database();
$db       = $database->getConnection();
$model    = new UsuarioModel($db);
$method   = $_SERVER['REQUEST_METHOD'];
$id       = $_GET['id'] ?? null;

if ($method === "GET") {

    if (isset($_GET['stats'])) {
        AuthMiddleware::requireRol(['admin']);
        $stats = $model->getEstadisticas($db);
        echo json_encode(["success" => true, "data" => $stats]);

    } elseif (isset($_GET['rol'])) {
        AuthMiddleware::verifyToken();
        $rol = $_GET['rol'];

        if ($rol !== 'peluquera') {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "Solo se puede consultar el rol peluquera"]);
            exit;
        }

        $stmt = $db->prepare(
            "SELECT id, nombre FROM usuarios WHERE rol = :rol AND activo = 1 ORDER BY nombre ASC"
        );
        $stmt->bindParam(":rol", $rol);
        $stmt->execute();
        echo json_encode(["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

    } else {
        AuthMiddleware::requireRol(['admin']);
        $stmt     = $model->getAll();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $usuarios]);
    }
} elseif ($method === "POST") {

    AuthMiddleware::requireRol(['admin']);
    $data = json_decode(file_get_contents("php://input"));

    if (!$data || !isset($data->nombre, $data->email, $data->password, $data->rol)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Faltan campos: nombre, email, password, rol"]);
        exit;
    }

    $rolesValidos = ['peluquera', 'cliente'];
    if (!in_array($data->rol, $rolesValidos)) {
        http_response_code(422);
        echo json_encode(["success" => false, "message" => "Rol no válido. Usa: peluquera o cliente"]);
        exit;
    }

    if (strlen($data->password) < 6) {
        http_response_code(422);
        echo json_encode(["success" => false, "message" => "La contraseña debe tener al menos 6 caracteres"]);
        exit;
    }

    if ($model->emailExists($data->email)) {
        http_response_code(409);
        echo json_encode(["success" => false, "message" => "El email ya está registrado"]);
        exit;
    }

    $nuevoId = $model->createConRol($data->nombre, $data->email, $data->password, $data->rol);

    if ($nuevoId) {
        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Usuario creado correctamente", "id" => $nuevoId]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error al crear el usuario"]);
    }
} elseif ($method === "PUT") {
    AuthMiddleware::requireRol(['admin']);
    $data = json_decode(file_get_contents("php://input"));
    $adminActual = AuthMiddleware::verifyToken();
    if ((int)$id === (int)$adminActual->id) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "No puedes desactivar tu propia cuenta"]);
        exit;
    }
    if (!$id || !isset($data->activo)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Faltan parámetros: id en URL y activo en body"]);
        exit;
    }

    $ok = $data->activo ? $model->activar($id) : $model->desactivar($id);

    echo json_encode([
        "success" => $ok,
        "message" => $ok
            ? ($data->activo ? "Usuario activado" : "Usuario desactivado")
            : "Error al actualizar el usuario"
    ]);
} elseif ($method === "DELETE") {

    $adminActual = AuthMiddleware::requireRol(['admin']);
    if (!$id) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Falta el id en la URL"]);
        exit;
    }

    if ((int)$id === (int)$adminActual->id) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "No puedes eliminar tu propia cuenta"]);
        exit;
    }

    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $ok = $stmt->execute();

    echo json_encode([
        "success" => $ok,
        "message" => $ok ? "Usuario eliminado" : "Error al eliminar el usuario"
    ]);

} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}