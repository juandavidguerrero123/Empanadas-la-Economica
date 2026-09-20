<?php
session_start();
require_once "../../config/database.php";
if (!isset($_SESSION["usuario_id"])) { header("Location: sesion.html?error=acceso"); exit; }

$carrito = $_SESSION["carrito"] ?? [];
$items = [];
$total = 0;
if ($carrito) {
    $ids = array_keys($carrito);
    $marcadores = implode(",", array_fill(0, count($ids), "?"));
    $consulta = $conexion->prepare("SELECT id, nombre, precio FROM productos WHERE estado = 1 AND id IN ($marcadores) ORDER BY nombre");
    $consulta->execute($ids);
    foreach ($consulta->fetchAll(PDO::FETCH_ASSOC) as $producto) {
        $cantidad = (int)$carrito[$producto["id"]];
        $subtotal = $cantidad * (float)$producto["precio"];
        $producto["cantidad"] = $cantidad; $producto["subtotal"] = $subtotal; $items[] = $producto; $total += $subtotal;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Carrito | Empanadas La Económica</title><link href="../css/style.css" rel="stylesheet"><link href="../css/menu.css" rel="stylesheet"><script src="../../assets/js/app.js" defer></script>
    </head>
    <body class="body">
        <header><nav>
            <div class="bloque"><img src="../../assets/imagenes/Logo.jpg" alt="Logo">
                <div class="text-logo">
                    <h1>Empanadas La Económica</h1> 
                    <p>Sabor casero, precio justo.</p>
                </div>
            </div>
            <div class="bloque"><a href="menu.php" class="btn-secondary">Seguir comprando</a>
            </div></nav>
        </header>
        <main class="main-layout contenido-carrito">
            <h2 class="titulo-seccion">Tu carrito</h2>
            <?php if (isset($_GET["mensaje"])): ?> 
                <div hidden data-swal="success" data-swal-titulo="Carrito actualizado" data-parametros-url="mensaje"><?= htmlspecialchars($_GET["mensaje"]) ?>
                </div>
            <?php endif; ?>
            <?php if (!$items): ?>
                <div class="estado-vacio">
                    <p>Tu carrito está vacío.</p>
                    <a class="btn-comprar enlace-boton" href="menu.php">Ver productos</a>
                </div>
                <?php else: ?>
                    <form action="../../controller/carrito.php" method="POST">
                            <table class="tabla-carrito">
                                <thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?><tr><td><?= htmlspecialchars($item["nombre"]) ?></td><td>$<?= number_format($item["precio"], 0, ",", ".") ?></td><td>
                                        <input type="number" min="0" name="cantidades[<?= (int)$item["id"] ?>]" value="<?= $item["cantidad"] ?>"></td><td>$<?= number_format($item["subtotal"], 0, ",", ".") ?></td><td>
                                        <button class="boton-enlace" name="producto_id" value="<?= (int)$item['id'] ?>" type="submit">Eliminar</button>                                        </td></tr>
                                    <?php endforeach; ?>
                                    </tbody>
                            </table>
                        <div class="resumen-carrito">
                            <strong>Total: $<?= number_format($total, 0, ",", ".") ?></strong>
                            <button class="btn-secondary" type="submit" name="accion" value="actualizar">Actualizar carrito</button>
                            <a class="btn-comprar enlace-boton" href="confirmar_compra.php">Continuar a confirmar compra</a>
                        </div>
                    </form>
            <?php endif; ?>
        </main>
        <footer>© 2025 Empanadas la Económica — Todos los derechos reservados</footer>
    </body>
</html>
