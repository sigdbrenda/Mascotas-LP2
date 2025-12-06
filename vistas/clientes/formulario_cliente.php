<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cliente</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { width: 350px; margin: auto; }
        input { width: 100%; padding: 10px; margin: 5px 0; }
        button { width: 100%; padding: 10px; background: green; color: white; border: none; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Registro de Cliente</h2>

<form method="POST" action="">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="telefono" placeholder="Teléfono" required>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="text" name="mascota" placeholder="Nombre de mascota" required>
    <input type="text" name="tipo" placeholder="Tipo de mascota" required>

    <button type="submit">Guardar</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../../modelos/cliente.php";

    $cliente = new Cliente();
    $cliente->registrarCliente(
        $_POST["nombre"],
        $_POST["telefono"],
        $_POST["email"],
        $_POST["mascota"],
        $_POST["tipo"]
    );

    echo "<p style='color:green; text-align:center;'>Cliente registrado correctamente.</p>";
}
?>

</body>
</html>
