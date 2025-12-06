<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/cliente.php';

requerir_login();
$usuario = usuario_actual();

$modelo_cliente = new cliente();

$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre         = trim($_POST['nombre'] ?? '');
    $apellido       = trim($_POST['apellido'] ?? '');
    $telefono       = trim($_POST['telefono'] ?? '');
    $correo         = trim($_POST['correo'] ?? '');
    $nombre_mascota = trim($_POST['nombre_mascota'] ?? '');
    $tipo_mascota   = trim($_POST['tipo_mascota'] ?? '');

    if ($nombre === '' || $apellido === '' || $telefono === '' || $correo === '') {
        $mensaje_error = 'complete nombre, apellido, teléfono y correo.';
    } else {
        try {
            // registra solo datos básicos en la tabla clientes
            $ok = $modelo_cliente->registrar_cliente($nombre, $apellido, $correo, $telefono);

            // si luego quieres guardar mascota, se podría hacer en otra tabla aparte

            if ($ok) {
                $mensaje_exito = 'cliente registrado correctamente.';
            } else {
                $mensaje_error = 'no se pudo registrar el cliente.';
            }
        } catch (exception $e) {
            $mensaje_error = 'error: ' . $e->getmessage();
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>registro de cliente | tienda mascotas g</title>
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
                <li class="nav-item"><a class="nav-link active" href="lista_clientes.php">clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="../ventas/formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a></li>
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="text-center mb-3">registro de cliente</h3>
                    <p class="text-muted text-center mb-4">
                        registre los datos básicos del cliente para asociar futuras ventas y recordatorios.
                    </p>

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
                            <label class="form-label" for="nombre">nombre</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                required
                                value="<?php echo htmlspecialchars($nombre ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="apellido">apellido</label>
                            <input
                                type="text"
                                class="form-control"
                                id="apellido"
                                name="apellido"
                                required
                                value="<?php echo htmlspecialchars($apellido ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="telefono">teléfono</label>
                            <input
                                type="text"
                                class="form-control"
                                id="telefono"
                                name="telefono"
                                required
                                value="<?php echo htmlspecialchars($telefono ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="correo">correo</label>
                            <input
                                type="email"
                                class="form-control"
                                id="correo"
                                name="correo"
                                required
                                value="<?php echo htmlspecialchars($correo ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="nombre_mascota">nombre de mascota (opcional)</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nombre_mascota"
                                name="nombre_mascota"
                                value="<?php echo htmlspecialchars($nombre_mascota ?? ''); ?>"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="tipo_mascota">tipo de mascota (opcional)</label>
                            <input
                                type="text"
                                class="form-control"
                                id="tipo_mascota"
                                name="tipo_mascota"
                                value="<?php echo htmlspecialchars($tipo_mascota ?? ''); ?>"
                            >
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            guardar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
