<?php

session_start();

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/html/sesion.html?error=acceso");
    exit;
}

$correo = trim($_POST["correo"] ?? "");
$password = $_POST["password"] ?? "";

if (empty($correo) || empty($password)) {
    header("Location: ../view/html/sesion.html?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../view/html/sesion.html?error=correo");
    exit;
}

$sql = "SELECT
            id,
            rol_id,
            nombre,
            apellido,
            correo,
            password,
            estado
        FROM usuarios
        WHERE correo = ?";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $correo
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: ../view/html/sesion.html?error=credenciales");
    exit;
}

if ($usuario["estado"] != 1) {
    header("Location: ../view/html/sesion.html?error=inactivo");
    exit;
}

if (!password_verify($password, $usuario["password"])) {
    header("Location: ../view/html/sesion.html?error=credenciales");
    exit;
}

/* Crear variables de sesión */
$_SESSION["usuario_id"] = $usuario["id"];
$_SESSION["rol_id"] = $usuario["rol_id"];
$_SESSION["nombre"] = $usuario["nombre"];
$_SESSION["apellido"] = $usuario["apellido"];
$_SESSION["correo"] = $usuario["correo"];

/* Redirigir al menú */
header("Location: ../view/html/menu.html");
exit;

?>