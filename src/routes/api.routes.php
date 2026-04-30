<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, ngrok-skip-browser-warning");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("ngrok-skip-browser-warning: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$request = strtok($_SERVER['REQUEST_URI'], '?');
$parts = explode('/api/', $request, 2);
$route = isset($parts[1]) ? $parts[1] : '';
$segments = explode("/", trim($route, "/"));
$resource = $segments[0] ?? null;
$id = $segments[1] ?? null;

if ($id)
    $_GET['id'] = $id;

switch ($resource) {
    case "mascotas":
        require_once __DIR__ . "/api/mascotas.routes.php";
        break;

    case "citas":
        require_once __DIR__ . "/api/citas.routes.php";
        break;

    case "login":
    case "usuarios":
        require_once __DIR__ . "/api/usuario.routes.php";
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "Ruta no encontrada: " . $resource
        ]);
        break;
}