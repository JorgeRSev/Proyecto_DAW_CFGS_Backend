<?php
require_once __DIR__ . "/../models/mascotaModel.php";

class MascotaController {
    private $model;

    public function __construct($db) {
        $this->model = new Mascota($db);
    }

    public function getAll() {
        $stmt = $this->model->getAllMascotas();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getById($id) {
        $mascota = $this->model->getMascotaById($id);
        echo json_encode($mascota);
    }

    public function create($data) {
        if(isset($data->nombre, $data->raza, $data->edad, $data->observaciones, $data->id_dueno)) {
            $success = $this->model->createMascota(
                $data->nombre,
                $data->raza,
                $data->edad,
                $data->observaciones,
                $data->id_dueno
            );
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
        }
    }

    public function update($id, $data) {
        if(isset($data->nombre, $data->raza, $data->edad, $data->observaciones)) {
            $success = $this->model->updateMascota(
                $id,
                $data->nombre,
                $data->raza,
                $data->edad,
                $data->observaciones
            );
            echo json_encode(["success" => $success]);
        } else {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
        }
    }

    public function delete($id) {
        $success = $this->model->lockMascota($id);
        echo json_encode(["success" => $success]);
    }
}