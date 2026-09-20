<?php

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol_id'] ?? null) != 1) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

require_once '../config/database.php';
require_once '../model/calificacion.php';

$calificacionModelo = new Calificacion($conexion);
$calificaciones = $calificacionModelo->obtenerTodas();

require_once '../view/admin/calificaciones/index.php';
