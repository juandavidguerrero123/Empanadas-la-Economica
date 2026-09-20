<?php

require_once '../config/database.php';
require_once '../model/contacto.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /view/html/contactanos.html');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL) || $mensaje === '' || mb_strlen($mensaje) > 1000) {
    header('Location: /view/html/contactanos.html?estado=error');
    exit;
}


try {
    $contacto = new Contacto($conexion);

    if (!$contacto->crear($nombre, $correo, $mensaje)) {
        throw new RuntimeException('No fue posible guardar el mensaje.');
    }
} catch (Throwable $e) {
    header('Location: /view/html/contactanos.html?estado=error');
    exit;
}

header('Location: /view/html/contactanos.html?estado=enviado');
exit;
