<?php
require_once __DIR__ . '/../config/seguridad.php';

cerrar_sesion();

header('location: login.php');
exit;
?>
