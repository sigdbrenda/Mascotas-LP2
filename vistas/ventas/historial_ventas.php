<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../config/conexion.php';

requerir_login();

$db   = new conexion();
$conn = $db->iniciar();

// obtener todas las ventas con su cliente
$sql = 'select v.id_venta,
               v.fecha,
               v.total,
               c.nombre,
               c.apellido
        from ventas v
        join clientes c on c.id_cliente = v.id_cliente
        order by v.fecha desc';
$ventas = $conn->query($sql)->fetchall();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>historial de ventas | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="../dashboard.php">
            <i class="bi bi-shop"></i> 
            <span>Tienda Mascotas G</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../dashboard.php">inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="../clientes/formulario_cliente.php">clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a></li>
                <li class="nav-item"><a class="nav-link" href="productos/lista_productos.php">productos</a></li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container">
    <h3 class="mb-3">historial de ventas</h3>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>cliente</th>
                            <th>fecha</th>
                            <th class="text-end">total (s/.)</th>
                            <th class="text-center">acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($ventas) === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3">no hay ventas registradas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $v): ?>
                            <tr>
                                <td><?php echo (int)$v['id_venta']; ?></td>
                                <td><?php echo htmlspecialchars($v['nombre'] . ' ' . $v['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($v['fecha']); ?></td>
                                <td class="text-end"><?php echo number_format($v['total'], 2); ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="boleta_venta.php?id=<?php echo (int)$v['id_venta']; ?>">
                                        ver boleta
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
