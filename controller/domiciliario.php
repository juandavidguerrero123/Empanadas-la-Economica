<?php

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol_id'] ?? null) != 3) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

require_once '../config/database.php';
require_once '../model/domiciliario.php';

$domiciliario = new Domiciliario($conexion);

if (empty($_SESSION['csrf_domiciliario'])) { $_SESSION['csrf_domiciliario'] = bin2hex(random_bytes(32)); }

function redirigirDomiciliario($mensaje, $tipo)
{
    header('Location: /controller/domiciliario.php?' . http_build_query(['mensaje' => $mensaje, 'tipo' => $tipo]));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $pedidoId = $_POST['pedido_id'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if (!hash_equals($_SESSION['csrf_domiciliario'], $token) || !ctype_digit($pedidoId)) {
        redirigirDomiciliario('La solicitud no pudo ser validada. Inténtalo nuevamente.', 'error');
    }

    if ($accion === 'aceptar') {
        [$exito, $mensaje] = $domiciliario->aceptarPedido((int) $pedidoId, (int) $_SESSION['usuario_id']);
        redirigirDomiciliario($mensaje, $exito ? 'success' : 'error');
    }
    if ($accion === 'finalizar') {
        [$exito, $mensaje] = $domiciliario->finalizarPedido((int) $pedidoId, (int) $_SESSION['usuario_id']);
        redirigirDomiciliario($mensaje, $exito ? 'success' : 'error');
    }
    redirigirDomiciliario('La acción solicitada no es válida.', 'error');
}

$pendientes = $domiciliario->obtenerPendientes();
$enCamino = $domiciliario->obtenerEnCamino((int) $_SESSION['usuario_id']);
require_once '../view/domiciliario/index.php';
