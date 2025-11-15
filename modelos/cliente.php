<?php
require_once "conexion.php";

class Cliente {

    private $conn;

    public function __construct() {
        $db = new conexion();      
        $this->conn = $db->iniciar(); 
    }

    // ⚡ 1) Registrar cliente
    public function registrarCliente($nombre, $telefono, $email, $mascota, $tipo) {
        $sql = "INSERT INTO clientes (nombre, telefono, email, nombre_mascota, tipo_mascota) 
                VALUES (:nombre, :telefono, :email, :mascota, :tipo)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nombre", $nombre);
        $stmt->bindValue(":telefono", $telefono);
        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":mascota", $mascota);
        $stmt->bindValue(":tipo", $tipo);

        return $stmt->execute();
    }

    // ⚡ 2) Listar clientes
    public function listarClientes() {
        $sql = "SELECT * FROM clientes";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
?>
