<?php
    
    Class Cita{
    private $conn;
    private $table = "citas";

    public function __construct($db){
        $this->conn = $db;
    }

    public function createCita($fecha, $hora, $id_mascota, $id_peluquera, $notas){
        $query ="INSERT INTO " . $this->table .
        "       SET fecha = :fecha,
                hora = :hora,
                id_mascota = :id_mascota,
                id_peluquera = :id_peluquera,
                notas = :notas";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":id_mascota", $id_mascota);
        $stmt->bindParam(":id_peluquera", $id_peluquera);
        $stmt->bindParam(":notas", $notas);

        return $stmt->execute();
    }

    public function getAllCitas(){
        $query ="SELECT * FROM " . $this->table .
                " ORDER BY fecha DESC, hora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getCitasById($id){
        $query ="SELECT * FROM " . $this->table .
                " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCitasByMascota($id_mascota){
        $query ="SELECT * FROM " . $this->table .
                " WHERE id_mascota = :id_mascota";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_mascota", $id_mascota);
        $stmt->execute();
        
        return $stmt;
    }

    public function getCitasByPeluquera($id_peluquera){
        $query ="SELECT * FROM " . $this->table .
                " WHERE id_peluquera = :id_peluquera";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_peluquera", $id_peluquera);
        $stmt->execute();

        return $stmt;
    }

    public function updateEstadoCita($id, $estado){
        $query ="UPDATE " . $this->table .
                " SET estado = :estado 
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function deleteCita($id){
        $query ="DELETE FROM " . $this->table .
                " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }
}