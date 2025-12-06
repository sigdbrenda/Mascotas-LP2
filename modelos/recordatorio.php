<?php
require_once __DIR__ . '/../config/conexion.php';

class recordatorio {

    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->iniciar();
    }

    public function crear_recordatorio($id_cliente, $fecha_programada, $motivo, $canal = 'email') {
        $sql = 'insert into recordatorios (id_cliente, fecha_programada, motivo, canal)
                values (:id_cliente, :fecha_programada, :motivo, :canal)';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->bindValue(':fecha_programada', $fecha_programada);
        $stmt->bindValue(':motivo', $motivo);
        $stmt->bindValue(':canal', $canal);

        return $stmt->execute();
    }

    public function listar_proximos($limite = 20) {
        $sql = 'select r.id_recordatorio,
                       r.fecha_programada,
                       r.motivo,
                       r.canal,
                       r.estado,
                       c.nombre,
                       c.apellido
                from recordatorios r
                join clientes c on c.id_cliente = r.id_cliente
                where r.estado = "pendiente"
                order by r.fecha_programada asc
                limit :limite';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listar_todos($limite = 50) {
        $sql = 'select r.id_recordatorio,
                       r.fecha_programada,
                       r.motivo,
                       r.canal,
                       r.estado,
                       r.creado_en,
                       c.nombre,
                       c.apellido
                from recordatorios r
                join clientes c on c.id_cliente = r.id_cliente
                order by r.fecha_programada desc
                limit :limite';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function marcar_enviado($id_recordatorio) {
        $sql = 'update recordatorios
                set estado = "enviado"
                where id_recordatorio = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id_recordatorio, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
