<?php
require_once __DIR__ . '/../config/conexion.php';

class venta {

    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->iniciar();
    }

    // registrar venta + detalles + actualizar stock
    public function registrar_venta(int $id_cliente, array $items): int {

        if (count($items) === 0) {
            throw new Exception('no hay productos en la venta.');
        }

        // calcular total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['precio_unitario'] * $item['cantidad'];
        }

        try {
            $this->conn->beginTransaction();

            // 1) cabecera de venta
            $sql_venta = 'insert into ventas (id_cliente, fecha, total)
                          values (:id_cliente, now(), :total)';

            $stmt = $this->conn->prepare($sql_venta);
            $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->bindValue(':total', $total);
            $stmt->execute();

            $id_venta = (int)$this->conn->lastInsertId();

            // 2) detalle (sin columna subtotal)
            $sql_detalle = 'insert into detalle_venta 
                            (id_venta, id_producto, cantidad, precio_unitario)
                            values (:id_venta, :id_producto, :cantidad, :precio_unitario)';

            $stmt_detalle = $this->conn->prepare($sql_detalle);

            // 3) actualización de stock
            $sql_stock = 'update productos 
                          set stock = stock - :cantidad 
                          where id_producto = :id_producto';

            $stmt_stock = $this->conn->prepare($sql_stock);

            foreach ($items as $item) {

                // DETALLE
                $stmt_detalle->bindValue(':id_venta', $id_venta, PDO::PARAM_INT);
                $stmt_detalle->bindValue(':id_producto', $item['id_producto'], PDO::PARAM_INT);
                $stmt_detalle->bindValue(':cantidad', $item['cantidad'], PDO::PARAM_INT);
                $stmt_detalle->bindValue(':precio_unitario', $item['precio_unitario']);
                $stmt_detalle->execute();

                // STOCK
                $stmt_stock->bindValue(':cantidad', $item['cantidad'], PDO::PARAM_INT);
                $stmt_stock->bindValue(':id_producto', $item['id_producto'], PDO::PARAM_INT);
                $stmt_stock->execute();
            }

            $this->conn->commit();
            return $id_venta;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    // ventas por cliente (para historial en el formulario_venta)
    public function obtener_ventas_por_cliente(int $id_cliente): array {
        $sql = 'select v.id_venta, v.fecha, v.total
                from ventas v
                where v.id_cliente = :id_cliente
                order by v.fecha desc';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // obtener venta con detalle (para la boleta)
    public function obtener_venta_con_detalle(int $id_venta): ?array {
        $sql_venta = 'select v.id_venta, v.fecha, v.total, 
                             c.nombre, c.apellido, c.email, c.telefono
                      from ventas v
                      join clientes c on c.id_cliente = v.id_cliente
                      where v.id_venta = :id_venta';

        $stmt = $this->conn->prepare($sql_venta);
        $stmt->bindValue(':id_venta', $id_venta, PDO::PARAM_INT);
        $stmt->execute();

        $venta = $stmt->fetch();
        if (!$venta) {
            return null;
        }

        // detalle: calculamos el subtotal en la consulta
        $sql_detalle = 'select d.id_producto, p.nombre_producto,
                               d.cantidad, d.precio_unitario,
                               (d.cantidad * d.precio_unitario) as subtotal
                        from detalle_venta d
                        join productos p on p.id_producto = d.id_producto
                        where d.id_venta = :id_venta';

        $stmt2 = $this->conn->prepare($sql_detalle);
        $stmt2->bindValue(':id_venta', $id_venta, PDO::PARAM_INT);
        $stmt2->execute();

        $venta['detalles'] = $stmt2->fetchAll();

        // alias para compatibilidad (por si en algún lado usan "detalle")
        $venta['detalle'] = $venta['detalles'];

        return $venta;
    }

    // alias en camelCase por si en otras partes lo llamas así
    public function registrarVenta(int $id_cliente, array $items): int {
        return $this->registrar_venta($id_cliente, $items);
    }

    public function obtenerVentasPorCliente(int $id_cliente): array {
        return $this->obtener_ventas_por_cliente($id_cliente);
    }

    public function obtenerVentaConDetalle(int $id_venta): ?array {
        return $this->obtener_venta_con_detalle($id_venta);
    }
}
?>
