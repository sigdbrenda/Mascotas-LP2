<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/producto.php';

requerir_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('location: lista_productos.php?msg=producto no válido');
    exit;
}

$modelo_producto = new producto();

try {
    $modelo_producto->eliminar_producto($id);
    header('location: lista_productos.php?msg=producto eliminado correctamente');
    exit;
} catch (exception $e) {
    header('location: lista_productos.php?msg=error al eliminar el producto');
    exit;
}
