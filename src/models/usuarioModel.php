<?php
class UsuarioModel {

    private $conn;
    private $table = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($nombre, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table . "
                  (nombre, email, password)
                  VALUES (:nombre, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre",   $nombre);
        $stmt->bindParam(":email",    $email);
        $stmt->bindParam(":password", $password);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getPasswordById($id) {
        $query = "SELECT password FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }

    public function cambiarPassword($id, $nuevaPassword) {
        $hash  = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table . "
                  SET password = :password WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hash);
        $stmt->bindParam(":id",       $id);
        return $stmt->execute();
    }

    public function getAll($rol = null) {
        if ($rol) {
            $query = "SELECT id, nombre, email, rol, activo
                      FROM " . $this->table . "
                      WHERE rol = :rol
                      ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":rol", $rol);
        } else {
            $query = "SELECT id, nombre, email, rol, activo
                      FROM " . $this->table . "
                      ORDER BY rol ASC, nombre ASC";
            $stmt = $this->conn->prepare($query);
        }
        $stmt->execute();
        return $stmt;
    }
    public function createConRol($nombre, $email, $password, $rol) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table . "
                  (nombre, email, password, rol)
                  VALUES (:nombre, :email, :password, :rol)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre",   $nombre);
        $stmt->bindParam(":email",    $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":rol",      $rol);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    public function desactivar($id) {
        $query = "UPDATE " . $this->table . "
                  SET activo = 0 WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    public function activar($id) {
        $query = "UPDATE " . $this->table . "
                  SET activo = 1 WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getEstadisticas($conn) {
        $stats = [];

        $stmt = $conn->query(
            "SELECT rol, COUNT(*) AS total
             FROM usuarios
             WHERE activo = 1
             GROUP BY rol"
        );
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats['usuarios'][$row['rol']] = (int) $row['total'];
        }

        $stmt = $conn->query(
            "SELECT estado, COUNT(*) AS total
             FROM citas
             GROUP BY estado"
        );
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats['citas'][$row['estado']] = (int) $row['total'];
        }

        $stmt = $conn->query(
            "SELECT COUNT(*) AS total FROM citas
             WHERE MONTH(fecha) = MONTH(CURDATE())
             AND YEAR(fecha) = YEAR(CURDATE())"
        );
        $stats['citas_este_mes'] = (int) $stmt->fetchColumn();

        return $stats;
    }
}