<?php
require_once __DIR__ . '/../config/seguridad.php';
require_once __DIR__ . '/../modelos/usuario.php';

$errores = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($user === '' || $pass === '') {
        $errores = 'ingrese usuario y contraseña.';
    } else {
        $modelo_usuario = new usuario();
        $datos = $modelo_usuario->buscar_por_usuario($user);

        if ($datos && $datos['password'] === md5($pass)) {
            $_SESSION['usuario'] = [
                'id'      => $datos['id_usuario'],
                'nombre'  => $datos['nombre'],
                'usuario' => $datos['usuario'],
                'rol'     => $datos['rol'],
            ];

            header('location: dashboard.php');
            exit;
        } else {
            $errores = 'credenciales incorrectas.';
        }
    }
}

if (esta_logueado()) {
    header('location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>login | tienda mascotas g</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center mb-3">tienda de mascotas g</h4>
                    <h5 class="text-center mb-4">inicio de sesión</h5>

                    <?php if ($errores !== ''): ?>
                        <div class="alert alert-danger py-2">
                            <?php echo htmlspecialchars($errores); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            ingresar
                        </button>
                    </form>
                </div>
                <div class="card-footer text-center text-muted small">
                    sistema de clientes, ventas y recordatorios
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
