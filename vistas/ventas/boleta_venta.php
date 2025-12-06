<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/venta.php';

requerir_login();

$id_venta = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_venta <= 0) {
    echo 'venta no válida';
    exit;
}

$modeloVenta = new venta();
$venta = $modeloVenta->obtener_venta_con_detalle($id_venta);

if (!$venta) {
    echo 'venta no encontrada';
    exit;
}

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>boleta de venta #<?php echo (int)$venta['id_venta']; ?> | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="../dashboard.php">
            <span class="me-2">🏬</span>tienda mascotas g
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../dashboard.php">inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="../clientes/lista_clientes.php">clientes</a></li>
                <li class="nav-item"><a class="nav-link active" href="formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a></li>
                <li class="nav-item"><a class="nav-link" href="../productos/lista_productos.php">productos</a></li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">boleta de venta #<?php echo (int)$venta['id_venta']; ?></h5>
                        <small class="text-muted">tienda mascotas g</small>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">fecha: <?php echo htmlspecialchars($venta['fecha']); ?></small>
                    </div>
                </div>
                <div class="card-body">

                    <h6 class="mb-2">datos del cliente</h6>
                    <p class="mb-1">
                        <strong><?php echo htmlspecialchars($venta['nombre'] . ' ' . $venta['apellido']); ?></strong>
                    </p>
                    <p class="mb-1">
                        correo:
                        <?php echo htmlspecialchars($venta['email'] ?? '—'); ?>
                    </p>
                    <p class="mb-3">
                        teléfono:
                        <?php echo htmlspecialchars($venta['telefono'] ?? '—'); ?>
                    </p>

                    <hr>

                    <h6 class="mb-3">detalle de la venta</h6>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>producto</th>
                                    <th class="text-center">cant.</th>
                                    <th class="text-end">p. unit. (s/.)</th>
                                    <th class="text-end">subtotal (s/.)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($venta['detalles'])): ?>
                                <?php foreach ($venta['detalles'] as $d): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($d['nombre_producto']); ?></td>
                                        <td class="text-center"><?php echo (int)$d['cantidad']; ?></td>
                                        <td class="text-end"><?php echo number_format($d['precio_unitario'], 2); ?></td>
                                        <td class="text-end"><?php echo number_format($d['subtotal'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        no hay detalle registrado para esta venta.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">total</th>
                                    <th class="text-end">s/ <?php echo number_format($venta['total'], 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="formulario_venta.php" class="btn btn-outline-secondary">nueva venta</a>
                        <button class="btn btn-primary" onclick="window.print()">imprimir boleta</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
