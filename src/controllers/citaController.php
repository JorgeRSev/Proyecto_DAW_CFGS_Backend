<?php
require_once __DIR__ . "/../models/citaModel.php";

class CitaController {
    private $model;

    public function __construct($db) {
        $this->model = new Cita($db);
    }

    public function getAll() {
        $stmt  = $this->model->getAllCitas();
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data"    => $citas
        ]);
    }

    public function getMisCitas($userId) {
        $stmt  = $this->model->getCitasByUsuario($userId);
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data"    => $citas
        ]);
    }

    public function create($data) {
        $ok = $this->model->createCita(
            $data->fecha,
            $data->hora,
            $data->id_mascota,
            $data->id_peluquera,
            $data->notas ?? null
        );

        if ($ok) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "Cita creada correctamente"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Error al crear la cita"
            ]);
        }
    }

    public function updateEstado($id, $data) {
        $estadosValidos = ['pendiente', 'confirmada', 'cancelada', 'completada'];

        if (!isset($data->estado) || !in_array($data->estado, $estadosValidos)) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Estado no válido. Valores aceptados: " . implode(', ', $estadosValidos)
            ]);
            return;
        }

        $cita = $this->model->getCitasById($id);
        if (!$cita) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Cita no encontrada"
            ]);
            return;
        }

        $ok = $this->model->updateEstadoCita($id, $data->estado);

        if ($ok) {
            echo json_encode([
                "success" => true,
                "message" => "Estado actualizado correctamente"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Error al actualizar el estado"
            ]);
        }
    }

    public function delete($id) {
        $cita = $this->model->getCitasById($id);
        if (!$cita) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Cita no encontrada"
            ]);
            return;
        }

        $ok = $this->model->deleteCita($id);

        if ($ok) {
            echo json_encode([
                "success" => true,
                "message" => "Cita eliminada correctamente"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Error al eliminar la cita"
            ]);
        }
    }
}