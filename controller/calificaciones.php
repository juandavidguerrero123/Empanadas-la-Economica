<?php

session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol_id'] ?? null) != 2) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

require_once '../config/database.php';
require_once '../model/calificacion.php';

$calificacionModelo = new Calificacion($conexion);

if (empty($_SESSION['csrf_calificaciones'])) { $_SESSION['csrf_calificaciones'] = bin2hex(random_bytes(32)); }

function redirigirCalificaciones($mensaje, $tipo)
{
    header('Location: /controller/calificaciones.php?' . http_build_query(['mensaje' => $mensaje, 'tipo' => $tipo]));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidoId = $_POST['pedido_id'] ?? '';
    $puntuacion = $_POST['puntuacion'] ?? '';
    $comentario = trim($_POST['comentario'] ?? '');

    if (
        !hash_equals($_SESSION['csrf_calificaciones'], $_POST['csrf_token'] ?? '') ||
        !ctype_digit($pedidoId) || !in_array($puntuacion, ['1', '2', '3', '4', '5'], true) ||
        mb_strlen($comentario) > 500
    ) {
        redirigirCalificaciones('Revisa la calificación y el comentario ingresados.', 'error');
    }

    if (!$calificacionModelo->crear((int) $pedidoId, (int) $_SESSION['usuario_id'], (int) $puntuacion, $comentario)) {
        redirigirCalificaciones('Este pedido no está disponible para calificar.', 'error');
    }

    redirigirCalificaciones('Gracias por calificar nuestro servicio.', 'success');
}

$pedidos = $calificacionModelo->obtenerPedidosEntregadosSinCalificar((int) $_SESSION['usuario_id']);
require_once '../view/calificaciones/index.php';
