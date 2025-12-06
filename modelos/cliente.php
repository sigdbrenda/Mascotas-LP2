<?php
require_once "conexion.php";

class cliente {

    private $conn;

    public function __construct() {
        $db = new conexion();      
        $this->conn = $db->iniciar(); 
    }

    // 1) registrar cliente
    public function registrar_cliente($nombre, $telefono, $email, $mascota, $tipo) {
        $sql = "insert into clientes (nombre, telefono, email, nombre_mascota, tipo_mascota) 
                values (:nombre, :telefono, :email, :mascota, :tipo)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindvalue(":nombre", $nombre);
        $stmt->bindvalue(":telefono", $telefono);
        $stmt->bindvalue(":email", $email);
        $stmt->bindvalue(":mascota", $mascota);
        $stmt->bindvalue(":tipo", $tipo);

        return $stmt->execute();
    }

    // 2) listar clientes
    public function listar_clientes() {
        $sql = "select * from clientes";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchall();
    }
}
?>
