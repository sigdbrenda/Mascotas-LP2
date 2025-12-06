<?php
require_once __DIR__ . '/../config/seguridad.php';
require_once __DIR__ . '/../config/conexion.php';

requerir_login();

$usuario = usuario_actual();

$db   = new conexion();
$conn = $db->iniciar();

// totales para las tarjetas
$total_clientes  = $conn->query('select count(*) as c from clientes')->fetch()['c'] ?? 0;
$total_productos = $conn->query('select count(*) as c from productos')->fetch()['c'] ?? 0;
$total_ventas    = $conn->query('select count(*) as c from ventas')->fetch()['c'] ?? 0;
$monto_ventas    = $conn->query('select ifnull(sum(total),0) as m from ventas')->fetch()['m'] ?? 0;

// ultimas ventas
$sql = 'select v.id_venta, v.fecha, v.total, c.nombre, c.apellido
        from ventas v
        join clientes c on c.id_cliente = v.id_cliente
        order by v.fecha desc
        limit 5';
$ultimas_ventas = $conn->query($sql)->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>dashboard | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap + iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<!-- navbar principal, mismo estilo para todo el sistema -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-shop me-2"></i>
            <span>Tienda Mascotas G</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navprincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navprincipal">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- aquí marcamos inicio como activo -->
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="clientes/lista_clientes.php">clientes</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="ventas/formulario_venta.php">ventas</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="recordatorios/formulario_recordatorio.php">recordatorios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="productos/lista_productos.php">productos</a></li>
            </ul>

            <span class="navbar-text me-3">
                <?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>
                (<?php echo htmlspecialchars($usuario['rol'] ?? ''); ?>)
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container">

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">clientes registrados</h6>
                    <p class="display-6 mb-0"><?php echo (int)$total_clientes; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success h-100">
                <div class="card-body">
                    <h6 class="card-title">productos activos</h6>
                    <p class="display-6 mb-0"><?php echo (int)$total_productos; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info h-100">
                <div class="card-body">
                    <h6 class="card-title">ventas registradas</h6>
                    <p class="display-6 mb-0"><?php echo (int)$total_ventas; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning h-100">
                <div class="card-body">
                    <h6 class="card-title">monto total vendido (s/.)</h6>
                    <p class="display-6 mb-0"><?php echo number_format($monto_ventas, 2); ?></p>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">últimas ventas</h5>
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>cliente</th>
                            <th>fecha</th>
                            <th class="text-end">total (s/.)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($ultimas_ventas) === 0): ?>
                        <tr>
                            <td colspan="4" class="text-center py-3">
                                no hay ventas registradas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ultimas_ventas as $v): ?>
                            <tr>
                                <td><?php echo (int)$v['id_venta']; ?></td>
                                <td><?php echo htmlspecialchars($v['nombre'] . ' ' . $v['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($v['fecha']); ?></td>
                                <td class="text-end"><?php echo number_format($v['total'], 2); ?></td>
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
