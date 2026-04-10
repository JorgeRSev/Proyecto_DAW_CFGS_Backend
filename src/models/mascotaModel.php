<?php

class Mascota {

    private $conn;
    private $table = "mascotas";

    public function __construct($db){
        $this->conn = $db;
    }

    public function createMascota ($nombre, $raza, $edad, $observaciones, $id_dueno){
        $query = "INSERT INTO " . $this->table . "
                (nombre, raza, edad, observaciones, id_dueno)
                VALUES
                (:nombre, :raza, :edad, :observaciones, :id_dueno)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":raza", $raza);
        $stmt->bindParam(":edad", $edad);
        $stmt->bindParam(":observaciones", $observaciones);
        $stmt->bindParam(":id_dueno", $id_dueno);

        return $stmt->execute();
    }

    public function getAllMascotas(){
        $query ="SELECT * FROM " . $this->table . " WHERE activa = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getMascotaById($id){
        $query ="SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMascotasByDueno($id_dueno){
        $query ="SELECT * FROM " . $this->table . "
                WHERE id_dueno = :id_dueno AND activa = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_dueno", $id_dueno);
        $stmt->execute();

        return $stmt;
    }

    public function updateMascota($id, $nombre,$raza,$edad,$observaciones){
        $query ="UPDATE ". $this->table ."
                SET nombre = :nombre,
                raza = :raza,
                edad = :edad,
                observaciones = :observaciones
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":raza", $raza);
        $stmt->bindParam(":edad",$edad);
        $stmt->bindParam(":observaciones", $observaciones);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function deleteMascota($id){
        $query ="DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function lockMascota($id){
        $query ="UPDATE " . $this->table . "
                SET activa = 0
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}