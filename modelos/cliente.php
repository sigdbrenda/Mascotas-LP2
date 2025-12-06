<?php
require_once __DIR__ . '/../config/conexion.php';

class cliente
{
    private $conn;

    public function __construct()
    {
        $db = new conexion();
        $this->conn = $db->iniciar();
    }

    // registrar cliente
    // columnas: nombre, apellido, email, telefono
    public function registrar_cliente(string $nombre, string $apellido, string $correo, string $telefono): bool
    {
        $sql = 'insert into clientes (nombre, apellido, email, telefono)
                values (:nombre, :apellido, :email, :telefono)';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre',   $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':email',    $correo);
        $stmt->bindValue(':telefono', $telefono);

        return $stmt->execute();
    }

    // listar todos los clientes
    public function listar_clientes(): array
    {
        $sql = 'select id_cliente, nombre, apellido, email, telefono
                from clientes
                order by id_cliente asc';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // 👉 alias usado por ventas y recordatorios
    public function obtenerTodos(): array
    {
        return $this->listar_clientes();
    }

    // obtener cliente por id (para edición, si lo necesitas)
    public function obtener_por_id(int $id): ?array
    {
        $sql = 'select id_cliente, nombre, apellido, email, telefono
                from clientes
                where id_cliente = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $fila = $stmt->fetch();
        return $fila === false ? null : $fila;
    }

    // actualizar cliente
    public function actualizar_cliente(
        int $id,
        string $nombre,
        string $apellido,
        string $correo,
        string $telefono
    ): bool {
        $sql = 'update clientes
                set nombre = :nombre,
                    apellido = :apellido,
                    email = :email,
                    telefono = :telefono
                where id_cliente = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre',   $nombre);
        $stmt->bindValue(':apellido', $apellido);
        $stmt->bindValue(':email',    $correo);
        $stmt->bindValue(':telefono', $telefono);
        $stmt->bindValue(':id',       $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // eliminar cliente
    public function eliminar_cliente(int $id): bool
    {
        $sql = 'delete from clientes where id_cliente = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
