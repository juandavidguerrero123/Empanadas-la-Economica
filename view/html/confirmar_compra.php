<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: sesion.html?error=acceso");
    exit;
}

$carrito = $_SESSION["carrito"] ?? [];
if (!$carrito) {
    header("Location: carrito.php");
    exit;
}

$ids = array_keys($carrito);
$marcadores = implode(",", array_fill(0, count($ids), "?"));
$consulta = $conexion->prepare("SELECT id, nombre, precio FROM productos WHERE estado = 1 AND id IN ($marcadores) ORDER BY nombre");
$consulta->execute($ids);
$productos = $consulta->fetchAll(PDO::FETCH_ASSOC);
$total = 0;

foreach ($productos as &$producto) {
    $producto["cantidad"] = (int) $carrito[$producto["id"]];
    $producto["subtotal"] = $producto["cantidad"] * (float) $producto["precio"];
    $total += $producto["subtotal"];
}
unset($producto);

$domicilios = $conexion->prepare("SELECT id, direccion, barrio, ciudad, referencia, telefono_contacto FROM domicilios WHERE usuario_id = ? AND estado = 1 ORDER BY id DESC");
$domicilios->execute([$_SESSION["usuario_id"]]);
$domicilios = $domicilios->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar compra | Empanadas La Económica</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
</head>
<body class="body">
    <header><nav><div class="bloque"><img src="../../assets/imagenes/Logo.jpg" alt="Logo"><div class="text-logo"><h1>Empanadas La Económica</h1><p>Sabor casero, precio justo.</p></div></div><div class="bloque"><a href="carrito.php" class="btn-secondary">Volver al carrito</a></div></nav></header>
    <main class="main-layout contenido-carrito">
        <h2 class="titulo-seccion">Confirma tu compra</h2>
        <?php if (isset($_GET["error"])): ?><div class="mensaje-alerta error"><?= htmlspecialchars($_GET["error"]) ?></div><?php endif; ?>
        <?php if (!$productos): ?><div class="estado-vacio"><p>Los productos del carrito ya no están disponibles.</p><a href="menu.php" class="btn-comprar enlace-boton">Volver al menú</a></div>
        <?php elseif (!$domicilios): ?><div class="estado-vacio"><p>No tienes una dirección activa registrada. Debes registrar una antes de finalizar la compra.</p></div>
        <?php else: ?>
        <form action="../../controller/procesar_compra.php" method="POST" class="form-confirmacion">
            <section class="panel-confirmacion"><h3>Productos que vas a comprar</h3>
                <ul class="lista-resumen"><?php foreach ($productos as $producto): ?><li><span><?= htmlspecialchars($producto["nombre"]) ?> × <?= $producto["cantidad"] ?></span><strong>$<?= number_format($producto["subtotal"], 0, ",", ".") ?></strong></li><?php endforeach; ?></ul>
                <p class="total-confirmacion">Total: $<?= number_format($total, 0, ",", ".") ?></p>
            </section>
            <section class="panel-confirmacion"><h3>Dirección de entrega</h3><p>Confirma a cuál de tus direcciones enviaremos el pedido:</p>
                <?php foreach ($domicilios as $indice => $domicilio): ?><label class="opcion-direccion"><input type="radio" name="domicilio_id" value="<?= (int)$domicilio["id"] ?>" <?= $indice === 0 ? "checked" : "" ?> required><span><strong><?= htmlspecialchars($domicilio["direccion"]) ?></strong><br><?= htmlspecialchars($domicilio["barrio"] . ", " . $domicilio["ciudad"]) ?><br>Teléfono: <?= htmlspecialchars($domicilio["telefono_contacto"]) ?><?= $domicilio["referencia"] ? " · " . htmlspecialchars($domicilio["referencia"]) : "" ?></span></label><?php endforeach; ?>
            </section>
            <section class="panel-confirmacion"><h3>Método de pago</h3><select name="metodo_pago" required><option value="">Selecciona un método</option><option>Efectivo</option><option>Transferencia bancaria</option><option>Nequi</option><option>Daviplata</option><option>Tarjeta contra entrega</option></select></section>
            <button type="submit" class="btn-comprar boton-finalizar">Confirmar y comprar</button>
        </form>
        <?php endif; ?>
    </main>
    <footer>© 2025 Empanadas la Económica — Todos los derechos reservados</footer>
</body>
</html>
