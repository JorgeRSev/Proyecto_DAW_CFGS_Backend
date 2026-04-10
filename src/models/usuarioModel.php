<?php

class UsuarioModel {

    private $conn;
    private $table = "usuarios";

    public function __construct($db){
        $this->conn = $db;
    }

    public function login($email){
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($nombre, $email, $password){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table . "
                  (nombre, email, password)
                  VALUES (:nombre, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function emailExists($email){
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}