<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/producto.php';

requerir_login();

$modelo_producto = new producto();

$id_producto    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$modo_edicion   = $id_producto > 0;

$nombre_producto = '';
$precio          = '';
$stock           = '';

$mensaje_exito = '';
$mensaje_error = '';

if ($modo_edicion) {
    $producto = $modelo_producto->obtener_por_id($id_producto);
    if (!$producto) {
        header('location: lista_productos.php?msg=producto no encontrado');
        exit;
    }
    $nombre_producto = $producto['nombre_producto'];
    $precio          = $producto['precio'];
    $stock           = $producto['stock'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_form         = (int)($_POST['id_producto'] ?? 0);
    $nombre_producto = trim($_POST['nombre_producto'] ?? '');
    $precio          = (float)($_POST['precio'] ?? 0);
    $stock           = (int)($_POST['stock'] ?? 0);

    if ($nombre_producto === '' || $precio <= 0 || $stock < 0) {
        $mensaje_error = 'complete nombre, precio (>0) y stock (>=0).';
    } else {
        try {
            if ($id_form > 0) {
                $ok = $modelo_producto->actualizar_producto($id_form, $nombre_producto, $precio, $stock);
                if ($ok) {
                    header('location: lista_productos.php?msg=producto actualizado correctamente');
                    exit;
                } else {
                    $mensaje_error = 'no se pudo actualizar el producto.';
                }
            } else {
                $ok = $modelo_producto->registrar_producto($nombre_producto, $precio, $stock);
                if ($ok) {
                    header('location: lista_productos.php?msg=producto registrado correctamente');
                    exit;
                } else {
                    $mensaje_error = 'no se pudo registrar el producto.';
                }
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
    <title><?php echo $modo_edicion ? 'editar producto' : 'nuevo producto'; ?> | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
                <li class="nav-item"><a class="nav-link" href="../clientes/lista_clientes.php">clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="../ventas/formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a></li>
                <li class="nav-item"><a class="nav-link active" href="lista_productos.php">productos</a></li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-3 text-center">
                        <?php echo $modo_edicion ? 'editar producto' : 'nuevo producto'; ?>
                    </h3>

                    <?php if ($mensaje_error !== ''): ?>
                        <div class="alert alert-danger py-2"><?php echo htmlspecialchars($mensaje_error); ?></div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <input type="hidden" name="id_producto" value="<?php echo (int)$id_producto; ?>">

                        <div class="mb-3">
                            <label class="form-label" for="nombre_producto">nombre</label>
                            <input type="text" class="form-control" id="nombre_producto"
                                   name="nombre_producto" required
                                   value="<?php echo htmlspecialchars($nombre_producto); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="precio">precio (s/.)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio"
                                   name="precio" required
                                   value="<?php echo htmlspecialchars($precio); ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="stock">stock</label>
                            <input type="number" min="0" class="form-control" id="stock"
                                   name="stock" required
                                   value="<?php echo htmlspecialchars($stock); ?>">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="lista_productos.php" class="btn btn-outline-secondary">
                                volver
                            </a>
                            <button type="submit" class="btn btn-success">
                                guardar
                            </button>
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
