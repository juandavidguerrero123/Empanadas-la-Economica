<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar servicio - Empanadas La Económica</title>
    <link rel="stylesheet" href="/view/css/admin.css">
    <script src="/assets/js/app.js" defer></script>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header"><div class="admin-logo"><img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica"></div><div><h1>Califica nuestro servicio</h1><p>Tu opinión nos ayuda a mejorar.</p></div></header>
        <main class="admin-content">
            <?php if (isset($_GET['mensaje'], $_GET['tipo'])): ?><div hidden data-swal="<?= $_GET['tipo'] === 'success' ? 'success' : 'error' ?>" data-swal-titulo="<?= $_GET['tipo'] === 'success' ? 'Gracias por tu opinión' : 'Revisa la información' ?>" data-parametros-url="mensaje,tipo"><?= htmlspecialchars($_GET['mensaje']) ?></div><?php endif; ?>
            <div class="admin-section-header"><h2>Pedidos entregados</h2><a href="/view/html/menu.php" class="btn-cancelar">Volver al menú</a></div>
            <?php if (!empty($pedidos)): ?>
                <div class="calificaciones-grid">
                    <?php foreach ($pedidos as $pedido): ?>
                        <article class="tarjeta-calificacion">
                            <h3>Pedido #<?= (int) $pedido['id'] ?></h3>
                            <p>Entregado el <?= htmlspecialchars($pedido['fecha_pedido']) ?> · Total: $<?= number_format($pedido['total'], 0, ',', '.') ?></p>
                            <form action="/controller/calificaciones.php" method="POST">
                                <input type="hidden" name="pedido_id" value="<?= (int) $pedido['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_calificaciones']) ?>">
                                <div class="campo-formulario"><label for="puntuacion-<?= (int) $pedido['id'] ?>">¿Cómo fue tu experiencia?</label><select id="puntuacion-<?= (int) $pedido['id'] ?>" name="puntuacion" required><option value="">Selecciona una calificación</option><option value="5">★★★★★ Excelente</option><option value="4">★★★★ Muy bueno</option><option value="3">★★★ Bueno</option><option value="2">★★ Regular</option><option value="1">★ Malo</option></select></div>
                                <div class="campo-formulario"><label for="comentario-<?= (int) $pedido['id'] ?>">Comentario <small>(opcional)</small></label><textarea id="comentario-<?= (int) $pedido['id'] ?>" name="comentario" rows="4" maxlength="500" placeholder="Cuéntanos cómo fue el servicio"></textarea></div>
                                <button type="submit" class="btn-admin">Enviar calificación</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="mensaje-vacio">No tienes pedidos entregados pendientes por calificar.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
