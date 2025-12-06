<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../modelos/cliente.php';

requerir_login();

$modeloCliente = new cliente();
$clientes      = $modeloCliente->listar_clientes();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>clientes | tienda mascotas g</title>
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
                <li class="nav-item"><a class="nav-link active" href="#">clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="../ventas/formulario_venta.php">ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a></li>
                <li class="nav-item"><a class="nav-link" href="../productos/lista_productos.php">productos</a></li>
            </ul>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">clientes registrados</h3>
        <a href="formulario_cliente.php" class="btn btn-success btn-sm">
            <i class="bi bi-person-plus"></i> nuevo cliente
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
                            <th>apellido</th>
                            <th>teléfono</th>
                            <th>correo</th>
                            <th class="text-center">acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (count($clientes) === 0): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3">no hay clientes registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td><?php echo (int)$c['id_cliente']; ?></td>
                                <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($c['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($c['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($c['email']); ?></td>
                                <td class="text-center">
                                    <a href="formulario_cliente.php?id=<?php echo (int)$c['id_cliente']; ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="confirmar_eliminar_cliente(
                                                <?php echo (int)$c['id_cliente']; ?>,
                                                '<?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido'], ENT_QUOTES); ?>'
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
function confirmar_eliminar_cliente(id, nombre) {
    Swal.fire({
        title: '¿Eliminar cliente?',
        text: 'Se eliminará a "' + nombre + '". Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'eliminar_cliente.php?id=' + id;
        }
    });

    return false;
}
</script>

</body>
</html>
