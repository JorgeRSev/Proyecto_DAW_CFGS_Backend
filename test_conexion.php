<?php

require_once "src/config/database.php";

$database = new Database();
$conn = $database->getConnection();

if($conn){
    echo "Conexión correcta";
} else {
    echo "Error de conexión";
}