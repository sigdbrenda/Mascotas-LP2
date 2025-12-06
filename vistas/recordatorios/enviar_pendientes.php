<?php
require_once __DIR__ . '/../../config/seguridad.php';
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../modelos/recordatorio.php';

requerir_login();

$modelo = new recordatorio();

$db   = new conexion();
$conn = $db->iniciar();

// seleccionar recordatorios vencidos
$sql = 'select id_recordatorio
        from recordatorios
        where estado = "pendiente"
          and fecha_programada <= now()';

$pendientes = $conn->query($sql)->fetchall();

$enviados = 0;

foreach ($pendientes as $p) {
    // aquí podrías llamar a una función que envíe mail real (phpmailer)
    // por ahora solo marcamos como enviados
    if ($modelo->marcar_enviado($p['id_recordatorio'])) {
        $enviados++;
    }
}

header('location: formulario_recordatorio.php?enviados=' . $enviados);
exit;
?>
