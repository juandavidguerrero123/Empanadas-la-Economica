<?php

session_start();
require_once "../config/database.php";

function volverConMensaje(string $mensaje): void
{
    header("Location: ../view/html/carrito.php?mensaje=" . urlencode($mensaje));
    exit;
}

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../view/html/sesion.html?error=acceso");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/html/menu.php");
    exit;
}

$accion = $_POST["accion"] ?? "";
$productoId = filter_input(INPUT_POST, "producto_id", FILTER_VALIDATE_INT);

if (!isset($_SESSION["carrito"]) || !is_array($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

if ($accion === "agregar") {
    $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

    if (!$productoId || !$cantidad) {
        header("Location: ../view/html/menu.php?error=cantidad");
        exit;
    }

    $consulta = $conexion->prepare("SELECT id FROM productos WHERE id = ? AND estado = 1");
    $consulta->execute([$productoId]);

    if (!$consulta->fetch()) {
        header("Location: ../view/html/menu.php?error=producto");
        exit;
    }

    $_SESSION["carrito"][$productoId] = ($_SESSION["carrito"][$productoId] ?? 0) + $cantidad;
    header("Location: ../view/html/menu.php?agregado=1");
    exit;
}

if ($accion === "actualizar") {
    $cantidades = $_POST["cantidades"] ?? [];

    if (!is_array($cantidades)) {
        volverConMensaje("No fue posible actualizar el carrito.");
    }

    foreach ($cantidades as $id => $cantidad) {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT);

        if (!$id || $cantidad === false) {
            continue;
        }

        if ($cantidad <= 0) {
            unset($_SESSION["carrito"][$id]);
        } else {
            $_SESSION["carrito"][$id] = $cantidad;
        }
    }

    volverConMensaje("Carrito actualizado.");
}

if ($accion === "eliminar" && $productoId) {
    unset($_SESSION["carrito"][$productoId]);
    volverConMensaje("Producto eliminado del carrito.");
}

header("Location: ../view/html/carrito.php");
exit;
