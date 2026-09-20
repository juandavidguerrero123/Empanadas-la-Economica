<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

require_once '../config/database.php';
require_once '../model/perfil.php';

$perfilModelo = new Perfil($conexion);
$usuarioId = (int) $_SESSION['usuario_id'];

if (empty($_SESSION['csrf_perfil'])) { $_SESSION['csrf_perfil'] = bin2hex(random_bytes(32)); }

function redirigirPerfil($mensaje = '', $tipo = '')
{
    $parametros = $mensaje !== '' ? '?' . http_build_query(['mensaje' => $mensaje, 'tipo' => $tipo]) : '';
    header('Location: /controller/perfil.php' . $parametros);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_perfil'], $_POST['csrf_token'] ?? '')) {
        redirigirPerfil('La solicitud no pudo ser validada. Inténtalo nuevamente.', 'error');
    }

    $datos = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'apellido' => trim($_POST['apellido'] ?? ''),
        'tipo_documento' => trim($_POST['tipo_documento'] ?? ''),
        'numero_documento' => trim($_POST['numero_documento'] ?? ''),
        'correo' => trim($_POST['correo'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'password' => $_POST['password'] ?? ''
    ];

    if (
        $datos['nombre'] === '' || $datos['apellido'] === '' || $datos['tipo_documento'] === '' ||
        !ctype_digit($datos['numero_documento']) || !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL) ||
        $datos['telefono'] === '' || ($datos['password'] !== '' && strlen($datos['password']) < 6)
    ) {
        redirigirPerfil('Completa los campos con información válida. La contraseña debe tener al menos 6 caracteres.', 'error');
    }

    if ($perfilModelo->existeCorreoODocumentoDeOtroUsuario($usuarioId, $datos['correo'], $datos['numero_documento'])) {
        redirigirPerfil('El correo o el número de documento ya pertenecen a otra cuenta.', 'error');
    }

    $perfilModelo->actualizar($usuarioId, $datos);
    $_SESSION['nombre'] = $datos['nombre'];
    $_SESSION['apellido'] = $datos['apellido'];
    $_SESSION['correo'] = $datos['correo'];
    redirigirPerfil('Tus datos personales fueron actualizados correctamente.', 'success');
}

$perfil = $perfilModelo->obtenerPorId($usuarioId);

if (!$perfil) {
    session_destroy();
    header('Location: /view/html/sesion.html?error=acceso');
    exit;
}

require_once '../view/perfil/index.php';
