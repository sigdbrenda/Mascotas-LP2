<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/cliente.php';

requerir_login();

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('location: lista_clientes.php');
    exit;
}

$modelo_cliente = new cliente();

try {
    $ok = $modelo_cliente->eliminar_cliente($id);

    if ($ok) {
        header('location: lista_clientes.php?msg=cliente+eliminado+correctamente');
    } else {
        header('location: lista_clientes.php?error=no+se+pudo+eliminar+el+cliente');
    }
} catch (pdoexception $e) {
    // probablemente tiene ventas relacionadas
    header('location: lista_clientes.php?error=no+se+puede+eliminar+un+cliente+con+ventas+registradas');
}
exit;
