<?php
require_once __DIR__ . "/../models/citaModel.php";

class CitaController {
    private $model;

    public function __construct($db) {
        $this->model = new Cita($db);
    }

    public function getAll() {
        $stmt = $this->model->getAllCitas();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById($id) {
        $cita = $this->model->getCitasById($id);
        echo json_encode($cita);
    }

    public function create($data) {
        if(isset($data->fecha, $data->hora, $data->id_mascota, $data->id_peluquera, $data->notas)) {
            $success = $this->model->createCita(
                $data->fecha,
                $data->hora,
                $data->id_mascota,
                $data->id_peluquera,
                $data->notas
            );
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
        }
    }

    public function update($id, $data) {
        if(isset($data->estado)) {
            $success = $this->model->updateEstadoCita($id, $data->estado);
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
        }
    }

    public function delete($id) {
        $success = $this->model->deleteCita($id);
        echo json_encode(["success" => $success]);
    }
}