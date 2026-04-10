<?php
require_once __DIR__ . "/../models/citaModel.php";

class CitaController {
    private $model;

    public function __construct($db){
        $this->model = new Cita($db);
    }

    public function getAll(){
        echo json_encode(["success" => true]);
    }

    public function create($data){
        $cita = $this->model->create(
            $data->fecha,
            $data->hora,
            $data->id_mascota,
            $data->id_peluquera,
            $data->notas ?? null
        );

        echo json_encode(["success" => $cita]);
    }

    public function update($id, $data){
        echo json_encode(["success" => true]);
    }

    public function delete($id){
        echo json_encode(["success" => true]);
    }

    public function getMisCitas($userId){

        $stmt = $this->model->getByUser($userId);

        echo json_encode([
            "success" => true,
            "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ]);
    }
}