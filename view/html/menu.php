<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: sesion.html?error=acceso");
    exit;
}

$productos = $conexion->query("SELECT id, nombre, descripcion, precio, imagen FROM productos WHERE estado = 1 ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$cantidadCarrito = array_sum($_SESSION["carrito"] ?? []);
$mensaje = (($_GET["compra"] ?? "") === "exitosa") ? "¡Compra realizada correctamente! Tu pedido está en proceso de entrega." : (isset($_GET["agregado"]) ? "Producto agregado al carrito." : "");
$error = $_GET["error"] ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | Empanadas La Económica</title>
    <link rel="icon" type="image/png" href="../../assets/imagenes/Logo.jpg">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body class="body">
    <header>
        <nav>
            <div class="bloque">
                <img src="../../assets/imagenes/Logo.jpg" alt="Logo Empanadas la Económica">
                <div class="text-logo"><h1>Empanadas La Económica</h1><p>Sabor casero, precio justo.</p></div>
            </div>
            <div class="bloque acciones-menu">
                <a href="carrito.php" class="btn-carrito"><i class="fa-solid fa-cart-shopping"></i> Carrito <span><?= $cantidadCarrito ?></span></a>
                <a href="../../controller/cerrar_sesion.php" class="btn-secondary">Cerrar sesión</a>
            </div>
        </nav>
    </header>
    <main class="main-layout">
        <?php if ($mensaje): ?><div class="mensaje-alerta exito"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="mensaje-alerta error">No fue posible agregar el producto. Verifica la cantidad e inténtalo otra vez.</div><?php endif; ?>
        <?php if (!$productos): ?>
            <div class="estado-vacio"><p>Aún no hay productos disponibles. Importa el archivo <code>database/productos_iniciales.sql</code>.</p></div>
        <?php endif; ?>
        <section class="main-container-menu">
            <?php foreach ($productos as $producto): ?>
                <article class="card-menu">
                    <img src="../../assets/imagenes/<?= rawurlencode($producto["imagen"] ?: "empanadas.jpg") ?>" alt="<?= htmlspecialchars($producto["nombre"]) ?>">
                    <div class="description-menu">
                        <div class="space-between"><strong><?= htmlspecialchars($producto["nombre"]) ?></strong><strong>$<?= number_format((float)$producto["precio"], 0, ",", ".") ?></strong></div>
                        <p><?= htmlspecialchars($producto["descripcion"]) ?></p>
                        <form action="../../controller/carrito.php" method="POST" class="form-agregar">
                            <input type="hidden" name="accion" value="agregar">
                            <input type="hidden" name="producto_id" value="<?= (int)$producto["id"] ?>">
                            <label>Cantidad <input type="number" name="cantidad" min="1" value="1" required></label>
                            <button class="btn-comprar" type="submit"><i class="fa-solid fa-plus"></i> Agregar</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <footer>© 2025 Empanadas la Económica — Todos los derechos reservados</footer>
<script>
window.addEventListener("load", function () {
    const alerta = document.querySelector(".mensaje-alerta");

    if (alerta) {
        setTimeout(function () {
            alerta.remove();

            const url = new URL(window.location.href);
            url.searchParams.delete("agregado");
            url.searchParams.delete("compra");
            url.searchParams.delete("error");

            window.history.replaceState({}, document.title, url.pathname);
        }, 4000);
    }
});
</script>
</body>
</html>
