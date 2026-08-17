<?php

session_start();
require_once "../config/database.php";

function regresarConfirmacion(string $error): void
{
    header("Location: ../view/html/confirmar_compra.php?error=" . urlencode($error));
    exit;
}

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../view/html/sesion.html?error=acceso");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/html/carrito.php");
    exit;
}

$carrito = $_SESSION["carrito"] ?? [];
$domicilioId = filter_input(INPUT_POST, "domicilio_id", FILTER_VALIDATE_INT);
$metodoPago = $_POST["metodo_pago"] ?? "";
$metodosValidos = ["Efectivo", "Transferencia bancaria", "Nequi", "Daviplata", "Tarjeta contra entrega"];

if (!$carrito) {
    regresarConfirmacion("El carrito está vacío.");
}

if (!$domicilioId || !in_array($metodoPago, $metodosValidos, true)) {
    regresarConfirmacion("Selecciona una dirección y un método de pago válidos.");
}

try {
    $conexion->beginTransaction();

    $direccion = $conexion->prepare("SELECT id FROM domicilios WHERE id = ? AND usuario_id = ? AND estado = 1");
    $direccion->execute([$domicilioId, $_SESSION["usuario_id"]]);

    if (!$direccion->fetch()) {
        throw new RuntimeException("La dirección seleccionada no está disponible.");
    }

    $producto = $conexion->prepare("SELECT id, precio FROM productos WHERE id = ? AND estado = 1 FOR UPDATE");
    $productos = [];
    $total = 0.0;

    foreach ($carrito as $productoId => $cantidad) {
        $productoId = filter_var($productoId, FILTER_VALIDATE_INT);
        $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT);

        if (!$productoId || !$cantidad || $cantidad < 1) {
            throw new RuntimeException("El carrito contiene una cantidad inválida.");
        }

        $producto->execute([$productoId]);
        $fila = $producto->fetch(PDO::FETCH_ASSOC);

        if (!$fila) {
            throw new RuntimeException("Uno de los productos ya no está disponible.");
        }

        $subtotal = (float) $fila["precio"] * $cantidad;
        $productos[] = ["id" => $fila["id"], "cantidad" => $cantidad, "precio" => $fila["precio"], "subtotal" => $subtotal];
        $total += $subtotal;
    }

    $pedido = $conexion->prepare("INSERT INTO pedidos (usuario_id, fecha_pedido, estado, total, observaciones, domicilio_id) VALUES (?, CURDATE(), ?, ?, ?, ?)");
    $pedido->execute([$_SESSION["usuario_id"], "En proceso de entrega", $total, "Método de pago: " . $metodoPago, $domicilioId]);

    $pedidoId = (int) $conexion->lastInsertId();
    $detalle = $conexion->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal, observaciones) VALUES (?, ?, ?, ?, ?, NULL)");

    foreach ($productos as $item) {
        $detalle->execute([$pedidoId, $item["id"], $item["cantidad"], $item["precio"], $item["subtotal"]]);
    }

    $conexion->commit();
    unset($_SESSION["carrito"]);
    header("Location: ../view/html/menu.php?compra=exitosa");
    exit;
} catch (Throwable $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    regresarConfirmacion($e->getMessage());
}

