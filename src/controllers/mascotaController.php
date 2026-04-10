<?php
require_once __DIR__ . "/../models/mascotaModel.php";

class MascotaController {
    private $model;

    public function __construct($db){
        $this->model = new Mascota($db);
    }

    public function getAll($userId){
        $stmt = $this->model->getMascotasByDueno($userId);

        echo json_encode([
            "success" => true,
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
    }

    public function getById($id){
        echo json_encode([
            "success" => true,
            "data" => $this->model->getMascotaById($id)
        ]);
    }

    public function create($data){
        $mascota = $this->model->createMascota(
            $data->nombre,
            $data->raza,
            $data->edad,
            $data->observaciones ?? null,
            $data->id_dueno
        );
        echo json_encode(["success" => $mascota]);
    }

    public function update($id, $data, $userId){
        $mascota = $this->model->updateMascota(
            $id,
            $data->nombre,
            $data->raza,
            $data->edad,
            $data->observaciones
        );
        echo json_encode(["success" => $mascota]);
    }

    public function delete($id, $userId){
        $mascota = $this->model->lockMascota($id);
        echo json_encode(["success" => $mascota]);
    }
}