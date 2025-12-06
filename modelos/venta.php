<?php
require_once __DIR__ . '/../config/conexion_ventas.php'; 

class Venta {
    
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function registrarVenta($idCliente, $total, $productos) {
        
        $this->db->begin_transaction();

        try {
            $sql_venta = "INSERT INTO ventas (id_cliente, total) VALUES (?, ?)";
            $stmt_venta = $this->db->prepare($sql_venta);
            
            $stmt_venta->bind_param("id", $idCliente, $total); 
            $stmt_venta->execute();

            $idVenta = $this->db->insert_id;

            $sql_detalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
            $stmt_detalle = $this->db->prepare($sql_detalle);

            foreach ($productos as $producto) {
                $stmt_detalle->bind_param(
                    "iiid",
                    $idVenta,
                    $producto['id_producto'],
                    $producto['cantidad'],
                    $producto['precio_unitario']
                );
                $stmt_detalle->execute();
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }


    public function obtenerVentasPorCliente($idCliente) {
        
        $sql = "SELECT id_venta, fecha, total FROM ventas WHERE id_cliente = ? ORDER BY fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bind_param("i", $idCliente);
        
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }



}
?>