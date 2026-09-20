<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: /view/html/menu.php');
    exit;
}

require_once '../config/database.php';
require_once '../model/pedido.php';

$pedido = new Pedido($conexion);

$pendientes = array_merge(
    $pedido->obtenerPorEstado('Pendiente de asignación'),
    $pedido->obtenerPorEstado('En proceso de entrega')
);
$enCamino = $pedido->obtenerPorEstado('En camino');
$entregados = $pedido->obtenerPorEstado('Entregado');

require_once '../view/admin/pedidos/index.php';
