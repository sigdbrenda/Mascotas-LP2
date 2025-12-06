<?php
require_once __DIR__ . '/../config/conexion.php';

class usuario {

    private $conn;

    public function __construct() {
        $db = new conexion();
        $this->conn = $db->iniciar();
    }

    public function buscar_por_usuario(string $usuario) {
        $sql = 'select * from usuarios where usuario = :usuario limit 1';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindvalue(':usuario', $usuario);
        $stmt->execute();

        return $stmt->fetch();
    }
}
?>
