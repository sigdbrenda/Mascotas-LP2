<?php
// modelos/Producto.php
require_once __DIR__ . '/../config/conexion_ventas.php';

class Producto {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function obtenerTodos() {
        $sql = "SELECT id_producto, nombre_producto, precio, stock FROM productos WHERE stock > 0";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
?>