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
require_once '../model/personal.php';

$personal = new Personal($conexion);
$rolesPermitidos = [1 => 'Administrador', 3 => 'Domiciliario'];

if (empty($_SESSION['csrf_personal'])) {
    $_SESSION['csrf_personal'] = bin2hex(random_bytes(32));
}

function redirigirPersonal($parametros = [])
{
    $consulta = $parametros ? '?' . http_build_query($parametros) : '';
    header('Location: /controller/personal.php' . $consulta);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_personal'], $token)) {
        redirigirPersonal([
            'mensaje' => 'La solicitud no pudo ser validada. Inténtalo nuevamente.',
            'tipo' => 'error'
        ]);
    }

    $accion = $_POST['accion'] ?? '';
    $rolId = $_POST['rol_id'] ?? '';

    if (!ctype_digit($rolId) || !isset($rolesPermitidos[(int) $rolId])) {
        redirigirPersonal([
            'mensaje' => 'El rol seleccionado no es válido.',
            'tipo' => 'error'
        ]);
    }

    $rolId = (int) $rolId;

    if ($accion === 'cambiar_estado') {
        $id = $_POST['id'] ?? '';
        $estado = $_POST['estado'] ?? '';

        if (ctype_digit($id) && in_array($estado, ['0', '1'], true)) {
            if ($rolId === 1 && (int) $id === (int) $_SESSION['usuario_id']) {
                redirigirPersonal([
                    'mensaje' => 'No puedes cambiar el estado de tu propia cuenta de administrador.',
                    'tipo' => 'error'
                ]);
            }

            $personal->cambiarEstado((int) $id, $rolId, (int) $estado);
            redirigirPersonal([
                'mensaje' => 'El estado de la cuenta fue actualizado correctamente.',
                'tipo' => 'success'
            ]);
        }

        redirigirPersonal([
            'mensaje' => 'No fue posible actualizar el estado de la cuenta.',
            'tipo' => 'error'
        ]);
    }

    if ($accion === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $tipoDocumento = trim($_POST['tipo_documento'] ?? '');
        $numeroDocumento = trim($_POST['numero_documento'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');

        if (
            $nombre === '' || $apellido === '' || $tipoDocumento === '' ||
            !ctype_digit($numeroDocumento) || !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
            strlen($password) < 6 || $telefono === ''
        ) {
            redirigirPersonal([
                'accion' => 'crear',
                'rol' => $rolId,
                'mensaje' => 'Completa los campos con información válida. La contraseña debe tener al menos 6 caracteres.',
                'tipo' => 'error'
            ]);
        }

        if ($personal->existeCorreoODocumento($correo, $numeroDocumento)) {
            redirigirPersonal([
                'accion' => 'crear',
                'rol' => $rolId,
                'mensaje' => 'El correo o el número de documento ya están registrados.',
                'tipo' => 'error'
            ]);
        }

        $personal->crear(
            $rolId,
            $nombre,
            $apellido,
            $tipoDocumento,
            $numeroDocumento,
            $correo,
            $password,
            $telefono
        );

        redirigirPersonal([
            'mensaje' => $rolesPermitidos[$rolId] . ' registrado correctamente.',
            'tipo' => 'success'
        ]);
    }

    redirigirPersonal();
}

if (($_GET['accion'] ?? '') === 'crear') {
    $rolId = $_GET['rol'] ?? '';

    if (!ctype_digit($rolId) || !isset($rolesPermitidos[(int) $rolId])) {
        redirigirPersonal();
    }

    $rolId = (int) $rolId;
    $nombreRol = $rolesPermitidos[$rolId];

    require_once '../view/admin/personal/crear.php';
    exit;
}

$administradores = $personal->obtenerPorRol(1);
$domiciliarios = $personal->obtenerPorRol(3);

require_once '../view/admin/personal/index.php';
