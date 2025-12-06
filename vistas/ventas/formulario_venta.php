<?php
require_once __DIR__ . '/../../config/conexion_ventas.php'; 
require_once __DIR__ . '/../../modelos/Venta.php';
require_once __DIR__ . '/../../modelos/cliente.php';
require_once __DIR__ . '/../../modelos/productos.php';

$modeloVenta = new Venta($conexion_ventas);
$modeloCliente = new Cliente($conexion_ventas);
$modeloProducto = new Producto($conexion_ventas); 

$listaClientes = $modeloCliente->obtenerTodos();
$listaProductos = $modeloProducto->obtenerTodos();
$historialCliente = $modeloVenta->obtenerVentasPorCliente(1); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Ventas | Tienda G</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-shop"></i> Tienda Mascotas G</a>
            <span class="navbar-text text-light">Módulo de Ventas</span>
        </div>
    </nav>

    <div class="container">
        
        <form action="procesar_venta.php" method="POST" id="formVenta">
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Agregar Productos</h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="mb-3">
                                <label for="cliente" class="form-label fw-bold">Cliente</label>
                                <select id="cliente" name="id_cliente" class="form-select" required>
                                    <option value="">-- Seleccione Cliente --</option>
                                    <?php foreach ($listaClientes as $cliente): ?>
                                        <option value="<?= $cliente['id_cliente'] ?>">
                                            <?= $cliente['nombre'] . " " . $cliente['apellido'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label for="producto" class="form-label fw-bold">Producto</label>
                                <select id="producto" class="form-select">
                                    <option value="">-- Seleccione Producto --</option>
                                    <?php foreach ($listaProductos as $prod): ?>
                                        <option value="<?= $prod['id_producto'] ?>" 
                                                data-precio="<?= $prod['precio'] ?>"
                                                data-nombre="<?= $prod['nombre_producto'] ?>"
                                                data-stock="<?= $prod['stock'] ?>">
                                            <?= $prod['nombre_producto'] ?> - S/ <?= $prod['precio'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="cantidad" class="form-label fw-bold">Cantidad</label>
                                <input type="number" id="cantidad" class="form-control" value="1" min="1">
                            </div>

                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary" onclick="agregarAlCarrito()">
                                    <i class="bi bi-plus-lg"></i> Agregar a la Lista
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white d-flex justify-content-between">
                            <h5 class="mb-0"><i class="bi bi-basket"></i> Detalle de Venta</h5>
                            <span class="badge bg-light text-success fs-6">Total: <span id="totalVenta">S/ 0.00</span></span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0 text-center align-middle" id="tablaCarrito">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cant.</th>
                                            <th>P. Unit.</th>
                                            <th>Subtotal</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="filaVacia">
                                            <td colspan="5" class="text-muted p-4">El carrito está vacío</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-end">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-check-circle-fill"></i> CONFIRMAR VENTA
                            </button>
                        </div>
                    </div>
                </div>
            </div> 
            
            <div id="inputsOcultos"></div>

        </form>

        <hr class="my-5">

        <h4 class="text-muted mb-3">Historial Reciente (Referencia)</h4>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr><th>ID</th><th>Fecha</th><th>Total</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($historialCliente as $venta): ?>
                        <tr>
                            <td>#<?= $venta['id_venta'] ?></td>
                            <td><?= $venta['fecha'] ?></td>
                            <td>S/ <?= $venta['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let totalGeneral = 0;
        let contadorProductos = 0;

        function agregarAlCarrito() {
            const selectProducto = document.getElementById('producto');
            const inputCantidad = document.getElementById('cantidad');
            
            const idProducto = selectProducto.value;
            const cantidad = parseInt(inputCantidad.value);
            
            if (idProducto === "") {
                alert("Por favor seleccione un producto");
                return;
            }
            if (cantidad < 1) {
                alert("La cantidad debe ser al menos 1");
                return;
            }

            const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
            const nombreProducto = opcionSeleccionada.getAttribute('data-nombre');
            const precioUnitario = parseFloat(opcionSeleccionada.getAttribute('data-precio'));
            
            const subtotal = precioUnitario * cantidad;

            const filaVacia = document.getElementById('filaVacia');
            if(filaVacia) filaVacia.style.display = 'none';

            const tbody = document.querySelector('#tablaCarrito tbody');
            const nuevaFila = document.createElement('tr');
            nuevaFila.id = `fila-${contadorProductos}`;
            
            nuevaFila.innerHTML = `
                <td class="text-start">${nombreProducto}</td>
                <td>${cantidad}</td>
                <td>S/ ${precioUnitario.toFixed(2)}</td>
                <td class="fw-bold">S/ ${subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${contadorProductos}, ${subtotal})">
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
            document.getElementById('totalVenta').innerText = `S/ ${totalGeneral.toFixed(2)}`;

            selectProducto.value = "";
            inputCantidad.value = 1;
            contadorProductos++;
        }

        function eliminarProducto(idFila, subtotal) {
            document.getElementById(`fila-${idFila}`).remove();
            
            document.getElementById(`inputs-${idFila}`).remove();

            totalGeneral -= subtotal;
            document.getElementById('totalVenta').innerText = `S/ ${totalGeneral.toFixed(2)}`;
        }
    </script>
</body>
</html>
<?php if(isset($conexion_ventas)) $conexion_ventas->close(); ?>