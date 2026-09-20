<?php

session_start();

/* Solo los administradores pueden consultar los clientes. */
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: /view/html/menu.php');
    exit;
}

require_once '../config/database.php';
require_once '../model/cliente.php';

$cliente = new Cliente($conexion);

if (empty($_SESSION['csrf_clientes'])) {
    $_SESSION['csrf_clientes'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (
        hash_equals($_SESSION['csrf_clientes'], $token) &&
        ctype_digit($id) &&
        in_array($estado, ['0', '1'], true)
    ) {
        $cliente->cambiarEstado((int) $id, (int) $estado);
    }

    header('Location: /controller/clientes.php?mensaje=estado_actualizado');
    exit;
}

if (($_GET['accion'] ?? '') === 'detalle') {
    $id = $_GET['id'] ?? '';

    if (!ctype_digit($id)) {
        header('Location: /controller/clientes.php');
        exit;
    }

    $clienteSeleccionado = $cliente->obtenerPorId((int) $id);

    if (!$clienteSeleccionado) {
        header('Location: /controller/clientes.php');
        exit;
    }

    $direcciones = $cliente->obtenerDirecciones((int) $id);

    require_once '../view/admin/clientes/detalle.php';
    exit;
}

$busqueda = trim($_GET['buscar'] ?? '');
$clientes = $cliente->obtenerTodos($busqueda);

require_once '../view/admin/clientes/index.php';
