<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/Venta.php';

requerir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('location: formulario_venta.php');
    exit;
}

$id_cliente = (int)($_POST['id_cliente'] ?? 0);
$productos_post = $_POST['productos'] ?? [];

if ($id_cliente <= 0 || empty($productos_post)) {
    echo 'datos de venta incompletos.';
    exit;
}

// armar arreglo de items
$items = [];
foreach ($productos_post as $p) {
    $id_producto     = (int)($p['id_producto'] ?? 0);
    $cantidad        = (int)($p['cantidad'] ?? 0);
    $precio_unitario = (float)($p['precio_unitario'] ?? 0);

    if ($id_producto > 0 && $cantidad > 0 && $precio_unitario > 0) {
        $items[] = [
            'id_producto'     => $id_producto,
            'cantidad'        => $cantidad,
            'precio_unitario' => $precio_unitario,
        ];
    }
}

if (count($items) === 0) {
    echo 'no hay productos válidos en la venta.';
    exit;
}

try {
    $modelo_venta = new venta();
    $id_venta = $modelo_venta->registrar_venta($id_cliente, $items);

    // redirigir a la boleta
    header('location: boleta_venta.php?id=' . $id_venta);
    exit;

} catch (exception $e) {
    echo 'error al registrar la venta: ' . $e->getmessage();
}
