<?php

define('DB_VENTAS_HOST', 'localhost');
define('DB_VENTAS_USER', 'root');
define('DB_VENTAS_PASS', '');
define('DB_VENTAS_NAME', 'mascotas_db');

$conexion_ventas = new mysqli(DB_VENTAS_HOST, DB_VENTAS_USER, DB_VENTAS_PASS, DB_VENTAS_NAME);

if ($conexion_ventas->connect_error) {
    die("Error de Conexión (Ventas): " . $conexion_ventas->connect_error);
}

$conexion_ventas->set_charset("utf8");
?>