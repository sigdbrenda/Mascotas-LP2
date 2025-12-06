<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/cliente.php';
require_once __DIR__ . '/../../modelos/recordatorio.php';

requerir_login();
$usuario = usuario_actual();

$modelo_cliente      = new cliente();
$modelo_recordatorio = new recordatorio();

$clientes      = $modelo_cliente->obtenerTodos();
$mensaje_exito = '';
$mensaje_error = '';

// procesar envio del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = (int)($_POST['id_cliente'] ?? 0);
    $fecha      = trim($_POST['fecha'] ?? '');
    $hora       = trim($_POST['hora'] ?? '');
    $motivo     = trim($_POST['motivo'] ?? '');
    $canal      = $_POST['canal'] ?? 'email';

    if ($id_cliente <= 0 || $fecha === '' || $hora === '' || $motivo === '') {
        $mensaje_error = 'complete todos los campos del recordatorio.';
    } else {
        $fecha_programada = $fecha . ' ' . $hora . ':00';

        try {
            $ok = $modelo_recordatorio->crear_recordatorio(
                $id_cliente,
                $fecha_programada,
                $motivo,
                $canal
            );
            if ($ok) {
                $mensaje_exito = 'recordatorio registrado correctamente.';
            } else {
                $mensaje_error = 'no se pudo registrar el recordatorio.';
            }
        } catch (exception $e) {
            $mensaje_error = 'error: ' . $e->getmessage();
        }
    }
}

// obtener proximos recordatorios
$proximos = $modelo_recordatorio->listar_proximos(20);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>recordatorios | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="../dashboard.php">
            <i class="bi bi-shop me-2"></i>
            <span>Tienda Mascotas G</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navprincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navprincipal">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../dashboard.php">inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="../clientes/lista_clientes.php">clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="../ventas/formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link active" href="formulario_recordatorio.php">recordatorios</a></li>
                <li class="nav-item"><a class="nav-link" href="../productos/lista_productos.php">productos</a></li>
            </ul>
            <span class="navbar-text me-3">
                <?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>
                (<?php echo htmlspecialchars($usuario['rol'] ?? ''); ?>)
            </span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mb-4">
    <div class="row g-4">
        <!-- formulario de nuevo recordatorio -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">nuevo recordatorio</h5>

                    <?php if ($mensaje_exito !== ''): ?>
                        <div class="alert alert-success py-2">
                            <?php echo htmlspecialchars($mensaje_exito); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($mensaje_error !== ''): ?>
                        <div class="alert alert-danger py-2">
                            <?php echo htmlspecialchars($mensaje_error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="id_cliente" class="form-label">cliente</label>
                            <select name="id_cliente" id="id_cliente" class="form-select" required>
                                <option value="">seleccione un cliente</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?php echo (int)$c['id_cliente']; ?>">
                                        <?php echo htmlspecialchars($c['nombre'] . ' ' . ($c['apellido'] ?? '')); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">fecha y hora</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <input type="time" name="hora" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motivo" class="form-label">motivo</label>
                            <textarea
                                name="motivo"
                                id="motivo"
                                rows="2"
                                class="form-control"
                                placeholder="ej: recordatorio de compra de alimento para mascota"
                                required
                            ></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="canal" class="form-label">canal</label>
                            <select name="canal" id="canal" class="form-select">
                                <option value="email">correo electrónico</option>
                                <option value="sms">sms (futuro)</option>
                                <option value="whatsapp">whatsapp (futuro)</option>
                            </select>
                            <div class="form-text">
                                en esta versión se registra el canal, y el envío se ejecuta desde el sistema.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            guardar recordatorio
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- tabla de próximos recordatorios -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">próximos recordatorios (pendientes)</h5>

                    <a href="enviar_pendientes.php" class="btn btn-sm btn-success mb-2">
                        enviar recordatorios vencidos
                    </a>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>cliente</th>
                                    <th>fecha programada</th>
                                    <th>motivo</th>
                                    <th>canal</th>
                                    <th>estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (count($proximos) === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        no hay recordatorios pendientes.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($proximos as $r): ?>
                                    <tr>
                                        <td><?php echo (int)$r['id_recordatorio']; ?></td>
                                        <td><?php echo htmlspecialchars($r['nombre'] . ' ' . $r['apellido']); ?></td>
                                        <td><?php echo htmlspecialchars($r['fecha_programada']); ?></td>
                                        <td><?php echo htmlspecialchars($r['motivo']); ?></td>
                                        <td><?php echo htmlspecialchars($r['canal']); ?></td>
                                        <td>
                                            <span class="badge bg-warning text-dark">pendiente</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <p class="text-muted small mt-2 mb-0">
                        estos recordatorios se pueden enviar manualmente o mediante una tarea programada (cron).
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
