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
    die("Todos los campos obligatorios deben estar completos.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("El correo electrónico no es válido.");
}

if (strlen($password) < 6) {
    die("La contraseña debe tener al menos 6 caracteres.");
}

$sql = "SELECT id FROM usuarios WHERE correo = ? OR numero_documento = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("ss", $correo, $numero_documento);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    die("El correo o el número de documento ya están registrados.");
}

$stmt->close();

$rol_id = 2;
$estado = 1;
$fecha_registro = date("Y-m-d");

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios 
        (rol_id, nombre, apellido, tipo_documento, numero_documento, correo, password, telefono, estado, fecha_registro)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "isssssssis",
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
);

if ($stmt->execute()) {

    $usuario_id = $conexion->insert_id;

    $estado_domicilio = 1;

    $sql_domicilio = "INSERT INTO domicilios
        (usuario_id, direccion, barrio, ciudad, referencia, telefono_contacto, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt_domicilio = $conexion->prepare($sql_domicilio);

    $stmt_domicilio->bind_param(
        "isssssi",
        $usuario_id,
        $direccion,
        $barrio,
        $ciudad,
        $referencia,
        $telefono,
        $estado_domicilio
    );

    if ($stmt_domicilio->execute()) {
        echo "Usuario y domicilio registrados correctamente.";
    } else {
        echo "Usuario registrado, pero ocurrió un error al guardar el domicilio: "
             . $stmt_domicilio->error;
    }

    $stmt_domicilio->close();

} else {
    echo "Error al registrar el usuario: " . $stmt->error;
}

$stmt->close();
$conexion->close();

?>