<?php
require_once _DIR_ . '/../config/conexion.php';

class Mascota {

    private $pdo;

    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    // Registrar mascota
    public function registrarMascota($cliente_id, $nombre, $especie, $edad) {
        $sql = "INSERT INTO mascotas (cliente_id, nombre, especie, edad)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$cliente_id, $nombre, $especie, $edad]);
    }

    // Listar mascotas
    public function listarMascotas() {
        $sql = "SELECT m.*, c.nombre AS cliente
                FROM mascotas m
                INNER JOIN clientes c ON c.id = m.cliente_id
                ORDER BY m.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}


