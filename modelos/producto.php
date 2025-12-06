<?php
require_once __DIR__ . '/../config/conexion.php';

class producto {

    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->iniciar();
    }

    // registrar producto
    public function registrar_producto($nombre_producto, $precio, $stock) {
        $sql = 'insert into productos (nombre_producto, precio, stock)
                values (:nombre_producto, :precio, :stock)';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre_producto', $nombre_producto);
        $stmt->bindValue(':precio', $precio);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // actualizar producto
    public function actualizar_producto($id_producto, $nombre_producto, $precio, $stock) {
        $sql = 'update productos
                   set nombre_producto = :nombre_producto,
                       precio          = :precio,
                       stock           = :stock
                 where id_producto     = :id_producto';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindValue(':nombre_producto', $nombre_producto);
        $stmt->bindValue(':precio', $precio);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // eliminar producto
    public function eliminar_producto($id_producto) {
        $sql = 'delete from productos where id_producto = :id_producto';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // obtener un producto por id
    public function obtener_por_id($id_producto) {
        $sql = 'select * from productos where id_producto = :id_producto';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // listar productos
    public function listar_productos() {
        $sql = 'select * from productos order by id_producto';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // alias para las vistas
    public function obtenerTodos() {
        return $this->listar_productos();
    }

    // === NUEVO: reducir stock al registrar venta ===
    public function reducir_stock($id_producto, $cantidad) {
        $sql = 'update productos
                   set stock = stock - :cantidad
                 where id_producto = :id_producto';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_producto', $id_producto, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
