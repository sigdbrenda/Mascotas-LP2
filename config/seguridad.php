<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esta_logueado(): bool {
    return isset($_SESSION['usuario']);
}

function usuario_actual() {
    return $_SESSION['usuario'] ?? null;
}

function requerir_login(): void {
    if (!esta_logueado()) {
        header('location: /Mascotas-LP2/vistas/login.php');
        exit;
    }
}

function cerrar_sesion(): void {
    session_unset();
    session_destroy();
}
?>
