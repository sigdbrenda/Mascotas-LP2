<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../config/conexion_ventas.php';
require_once __DIR__ . '/../../modelos/Venta.php';
require_once __DIR__ . '/../../modelos/cliente.php';
require_once __DIR__ . '/../../modelos/producto.php';

requerir_login();
$usuario = usuario_actual();

// modelos
$modeloVenta    = new Venta($conexion_ventas);          // sigue con mysqli
$modeloCliente  = new cliente();                        // ahora usa pdo interno
$modeloProducto = new Producto($conexion_ventas);       // sigue con mysqli

// datos para los select e historial
$listaClientes   = $modeloCliente->obtenerTodos();
$listaProductos  = $modeloProducto->obtenerTodos();
$historialCliente = $modeloVenta->obtener_ventas_por_cliente(1);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>módulo de ventas | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap + iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<!-- navbar unificada -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
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
                <li class="nav-item">
                    <a class="nav-link" href="../dashboard.php">inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../clientes/lista_clientes.php">clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="formulario_venta.php">ventas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../recordatorios/formulario_recordatorio.php">recordatorios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../productos/lista_productos.php">productos</a>
                </li>
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

    <form action="procesar_venta.php" method="post" id="formVenta">
        <div class="row">
            <!-- columna izquierda: selección -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cart-plus"></i> agregar productos
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="cliente" class="form-label fw-bold">cliente</label>
                            <select id="cliente" name="id_cliente" class="form-select" required>
                                <option value="">-- seleccione cliente --</option>
                                <?php foreach ($listaClientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id_cliente']; ?>">
                                        <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="producto" class="form-label fw-bold">producto</label>
                            <select id="producto" class="form-select">
                                <option value="">-- seleccione producto --</option>
                                <?php foreach ($listaProductos as $prod): ?>
                                    <option
                                        value="<?php echo $prod['id_producto']; ?>"
                                        data-precio="<?php echo $prod['precio']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($prod['nombre_producto']); ?>"
                                        data-stock="<?php echo $prod['stock']; ?>"
                                    >
                                        <?php echo htmlspecialchars($prod['nombre_producto']); ?>
                                        - s/ <?php echo number_format($prod['precio'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cantidad" class="form-label fw-bold">cantidad</label>
                            <input type="number" id="cantidad" class="form-control" value="1" min="1">
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary" onclick="agregarAlCarrito()">
                                <i class="bi bi-plus-lg"></i> agregar a la lista
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- columna derecha: carrito -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between">
                        <h5 class="mb-0">
                            <i class="bi bi-basket"></i> detalle de venta
                        </h5>
                        <span class="badge bg-light text-success fs-6">
                            total: <span id="totalVenta">s/ 0.00</span>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 text-center align-middle" id="tablaCarrito">
                                <thead class="table-light">
                                    <tr>
                                        <th>producto</th>
                                        <th>cant.</th>
                                        <th>p. unit.</th>
                                        <th>subtotal</th>
                                        <th>acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="filaVacia">
                                        <td colspan="5" class="text-muted p-4">
                                            el carrito está vacío
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-end">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-check-circle-fill"></i> confirmar venta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- inputs ocultos para enviar los productos -->
        <div id="inputsOcultos"></div>
    </form>

    <hr class="my-5">

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let totalGeneral = 0;
    let contadorProductos = 0;

    function agregarAlCarrito() {
        const selectProducto = document.getElementById('producto');
        const inputCantidad  = document.getElementById('cantidad');

        const idProducto = selectProducto.value;
        const cantidad   = parseInt(inputCantidad.value);

        if (idProducto === "") {
            alert("por favor seleccione un producto");
            return;
        }
        if (cantidad < 1) {
            alert("la cantidad debe ser al menos 1");
            return;
        }

        const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
        const nombreProducto = opcionSeleccionada.getAttribute('data-nombre');
        const precioUnitario = parseFloat(opcionSeleccionada.getAttribute('data-precio'));

        const subtotal = precioUnitario * cantidad;

        const filaVacia = document.getElementById('filaVacia');
        if (filaVacia) filaVacia.style.display = 'none';

        const tbody   = document.querySelector('#tablaCarrito tbody');
        const nuevaFila = document.createElement('tr');
        nuevaFila.id = `fila-${contadorProductos}`;

        nuevaFila.innerHTML = `
            <td class="text-start">${nombreProducto}</td>
            <td>${cantidad}</td>
            <td>s/ ${precioUnitario.toFixed(2)}</td>
            <td class="fw-bold">s/ ${subtotal.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger"
                        onclick="eliminarProducto(${contadorProductos}, ${subtotal})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(nuevaFila);

        const divOcultos = document.getElementById('inputsOcultos');
        divOcultos.innerHTML += `
            <div id="inputs-${contadorProductos}">
                <input type="hidden" name="productos[${contadorProductos}][id_producto]" value="${idProducto}">
                <input type="hidden" name="productos[${contadorProductos}][cantidad]" value="${cantidad}">
                <input type="hidden" name="productos[${contadorProductos}][precio_unitario]" value="${precioUnitario}">
            </div>
        `;

        totalGeneral += subtotal;
        document.getElementById('totalVenta').innerText = `s/ ${totalGeneral.toFixed(2)}`;

        selectProducto.value = "";
        inputCantidad.value  = 1;
        contadorProductos++;
    }

    function eliminarProducto(idFila, subtotal) {
        const fila = document.getElementById(`fila-${idFila}`);
        if (fila) fila.remove();

        const inputs = document.getElementById(`inputs-${idFila}`);
        if (inputs) inputs.remove();

        totalGeneral -= subtotal;
        if (totalGeneral < 0) totalGeneral = 0;
        document.getElementById('totalVenta').innerText = `s/ ${totalGeneral.toFixed(2)}`;
    }
</script>

<?php
// cerrar conexión mysqli de ventas si existe
if (isset($conexion_ventas)) {
    $conexion_ventas->close();
}
?>
</body>
</html>
