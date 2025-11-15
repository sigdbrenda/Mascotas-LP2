<?php

require_once __DIR__ . '/../../config/conexion_ventas.php'; 

require_once __DIR__ . '/../../modelos/Venta.php';

$modeloVenta = new Venta($conexion_ventas);

$historialCliente1 = $modeloVenta->obtenerVentasPorCliente(1);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        form { background-color: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, button { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Módulo de Ventas</h1>

    <h2>Registrar Nueva Venta</h2>
    <form action="procesar_venta.php" method="POST">
        
        <div>
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="id_cliente" required>
                <option value="1">Ana Gomez</option>
                <option value="2">Luis Torres</option>
            </select>
        </div>

        <p><strong>Agregar Productos a la Venta:</strong></p>

        <div>
            <label for="producto">Producto:</label>
            <select id="producto" name="id_producto">
                <option value="1">Comida para Perro 2kg (S/ 45.50)</option>
                <option value="2">Juguete Hueso Goma (S/ 15.00)</option>
                <option value="3">Arena para Gato 5kg (S/ 70.00)</option>
            </select>
        </div>

        <div>
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" value="1" min="1">
        </div>
        
        <button type="submit">Registrar Venta</button>
    </form>


    <h2>Historial de Ventas (Cliente: Ana Gomez)</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($historialCliente1) > 0) {
                foreach ($historialCliente1 as $venta) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($venta['id_venta']) . "</td>";
                    echo "<td>" . htmlspecialchars($venta['fecha']) . "</td>";
                    echo "<td>S/ " . htmlspecialchars($venta['total']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Este cliente no tiene ventas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
$conexion_ventas->close();
?>