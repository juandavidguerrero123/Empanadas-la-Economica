<?php

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Acceso no permitido.");
}

$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$tipo_documento = trim($_POST["tipo_documento"] ?? "");
$numero_documento = trim($_POST["numero_documento"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$password = $_POST["password"] ?? "";
$direccion = trim($_POST["direccion"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$barrio = trim($_POST["barrio"] ?? "");
$ciudad = trim($_POST["ciudad"] ?? "");
$referencia = trim($_POST["referencia"] ?? "");

if (
    empty($nombre) ||
    empty($apellido) ||
    empty($tipo_documento) ||
    empty($numero_documento) ||
    empty($correo) ||
    empty($password) ||
    empty($direccion) ||
    empty($barrio) ||
    empty($ciudad) ||
    empty($telefono)
) {
    header("Location: ../view/html/registrate.html?error=campos");
exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../view/html/registrate.html?error=correo");
exit;
}

if (strlen($password) < 6) {
    header("Location: ../view/html/registrate.html?error=password");
exit;
}

/* Comprobar si el correo o documento ya están registrados */
$sql = "SELECT id FROM usuarios WHERE correo = ? OR numero_documento = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $correo,
    $numero_documento
]);

if ($stmt->fetch()) {
    header("Location: ../view/html/registrate.html?error=duplicado");
exit;
}

/* Datos automáticos del usuario */
$rol_id = 2;
$estado = 1;
$fecha_registro = date("Y-m-d");

$password_hash = password_hash($password, PASSWORD_DEFAULT);

/* Registrar usuario */
$sql = "INSERT INTO usuarios
        (rol_id, nombre, apellido, tipo_documento, numero_documento, correo, password, telefono, estado, fecha_registro)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $rol_id,
    $nombre,
    $apellido,
    $tipo_documento,
    $numero_documento,
    $correo,
    $password_hash,
    $telefono,
    $estado,
    $fecha_registro
]);

/* Obtener el ID del usuario recién creado */
$usuario_id = $conexion->lastInsertId();

/* Registrar domicilio */
$estado_domicilio = 1;

$sql_domicilio = "INSERT INTO domicilios
    (usuario_id, direccion, barrio, ciudad, referencia, telefono_contacto, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt_domicilio = $conexion->prepare($sql_domicilio);

$stmt_domicilio->execute([
    $usuario_id,
    $direccion,
    $barrio,
    $ciudad,
    $referencia,
    $telefono,
    $estado_domicilio
]);

header("Location: ../view/html/sesion.html?registro=exitoso");
exit;

?>