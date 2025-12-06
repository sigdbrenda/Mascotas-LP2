<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/cliente.php';

requerir_login();

$modelo_cliente = new cliente();
$mensaje_exito  = '';
$mensaje_error  = '';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('location: lista_clientes.php');
    exit;
}

// obtener datos actuales
$cliente = $modelo_cliente->obtener_por_id($id);
if (!$cliente) {
    header('location: lista_clientes.php?error=cliente+no+encontrado');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');

    if ($nombre === '' || $apellido === '' || $telefono === '' || $correo === '') {
        $mensaje_error = 'complete nombre, apellido, teléfono y correo.';
    } else {
        try {
            $ok = $modelo_cliente->actualizar_cliente(
                $id,
                $nombre,
                $apellido,
                $correo,
                $telefono
            );

            if ($ok) {
                $mensaje_exito = 'cliente actualizado correctamente.';
                // actualizar datos en memoria para que se vean en el formulario
                $cliente = $modelo_cliente->obtener_por_id($id);
            } else {
                $mensaje_error = 'no se pudo actualizar el cliente.';
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
    <title>editar cliente | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                <li class="nav-item"><a class="nav-link active" href="lista_clientes.php">clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="../ventas/formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a></li>
                <li class="nav-item"><a class="nav-link" href="../productos/lista_productos.php">productos</a></li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="text-center mb-3">editar cliente</h3>

                    <?php if ($mensaje_exito !== ''): ?>
                        <div class="alert alert-success py-2"><?php echo htmlspecialchars($mensaje_exito); ?></div>
                    <?php endif; ?>

                    <?php if ($mensaje_error !== ''): ?>
                        <div class="alert alert-danger py-2"><?php echo htmlspecialchars($mensaje_error); ?></div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label" for="nombre">nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="apellido">apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido"
                                   value="<?php echo htmlspecialchars($cliente['apellido']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="telefono">teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono"
                                   value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="correo">correo</label>
                            <input type="email" class="form-control" id="correo" name="correo"
                                   value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">guardar cambios</button>
                            <a href="lista_clientes.php" class="btn btn-secondary">volver a la lista</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
