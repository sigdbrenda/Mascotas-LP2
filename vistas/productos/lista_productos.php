<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/producto.php';

requerir_login();

$modeloProducto = new producto();
$productos      = $modeloProducto->listar_productos();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>productos | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- iconos -->
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
                <li class="nav-item"><a class="nav-link active" href="#">productos</a></li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">productos</h3>
        <a href="formulario_producto.php" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg"></i> nuevo producto
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>nombre</th>
                            <th>precio (s/.)</th>
                            <th>stock</th>
                            <th class="text-center">acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($productos) === 0): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                no hay productos registrados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><?php echo (int)$p['id_producto']; ?></td>
                                <td><?php echo htmlspecialchars($p['nombre_producto']); ?></td>
                                <td><?php echo number_format($p['precio'], 2); ?></td>
                                <td><?php echo (int)$p['stock']; ?></td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="formulario_producto.php?id=<?php echo (int)$p['id_producto']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirmar_eliminar(
                                                <?php echo (int)$p['id_producto']; ?>,
                                                '<?php echo htmlspecialchars($p['nombre_producto'], ENT_QUOTES); ?>'
                                            );">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// popup bonito para eliminar
function confirmar_eliminar(id, nombre) {
    Swal.fire({
        title: '¿Eliminar producto?',
        text: 'Se eliminará "' + nombre + '". Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // redirigimos a la página de eliminación real
            window.location.href = 'eliminar_producto.php?id=' + id;
        }
    });

    // evitamos que el botón haga otra cosa
    return false;
}
</script>

</body>
</html>
