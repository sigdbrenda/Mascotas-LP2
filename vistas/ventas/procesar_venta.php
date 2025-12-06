<?php

require_once __DIR__ . '/../../config/conexion_ventas.php';
require_once __DIR__ . '/../../modelos/Venta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $idCliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : null;
    
    $productos = isset($_POST['productos']) ? $_POST['productos'] : [];

    if (empty($idCliente) || empty($productos)) {
        echo "<script>alert('Error: Faltan datos (Cliente o Productos).'); window.history.back();</script>";
        exit;
    }

    $totalVenta = 0;
    foreach ($productos as $item) {
        $totalVenta += ($item['cantidad'] * $item['precio_unitario']);
    }
   
    $modeloVenta = new Venta($conexion_ventas);
    $resultado = $modeloVenta->registrarVenta($idCliente, $totalVenta, $productos);

    if ($resultado) {
        echo "<script>
            alert('✅ ¡Venta registrada correctamente!');
            window.location.href = 'formulario_venta.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al registrar la venta en la Base de Datos.');
            window.history.back();
        </script>";
    }

} else {
    header("Location: formulario_venta.php");
    exit;
}
?>