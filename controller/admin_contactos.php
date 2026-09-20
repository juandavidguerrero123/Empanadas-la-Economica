<?php

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol_id'] ?? null) != 1) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

require_once '../config/database.php';
require_once '../model/contacto.php';

$contacto = new Contacto($conexion);
$contactos = $contacto->obtenerTodos();

require_once '../view/admin/contactos/index.php';
