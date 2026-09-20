<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregas - Empanadas La Económica</title>
    <link rel="stylesheet" href="/view/css/admin.css">
    <script src="/assets/js/app.js" defer></script>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-logo"><img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica"></div>
            <div><h1>Panel de entregas</h1><p>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?> · <a href="/controller/perfil.php">Mi perfil</a></p></div>
        </header>
        <main class="admin-content">
            <?php if (isset($_GET['mensaje'], $_GET['tipo'])): ?>
                <div hidden data-swal="<?= $_GET['tipo'] === 'success' ? 'success' : 'error' ?>" data-swal-titulo="<?= $_GET['tipo'] === 'success' ? 'Operación exitosa' : 'Revisa la información' ?>" data-parametros-url="mensaje,tipo"><?= htmlspecialchars($_GET['mensaje']) ?></div>
            <?php endif; ?>
            <section class="entregas-seccion">
                <h2>Pedidos pendientes por aceptar</h2>
                <?php if (!empty($pendientes)): ?><div class="entregas-grid">
                    <?php foreach ($pendientes as $pedido): ?>
                        <article class="tarjeta-entrega">
                            <h3>Pedido #<?= (int) $pedido['id'] ?></h3>
                            <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nombre']) ?> <?= htmlspecialchars($pedido['apellido']) ?></p>
                            <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
                            <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?>, <?= htmlspecialchars($pedido['barrio']) ?>, <?= htmlspecialchars($pedido['ciudad']) ?></p>
                            <p><strong>Contacto de entrega:</strong> <?= htmlspecialchars($pedido['telefono_contacto']) ?></p>
                            <?php if ($pedido['referencia']): ?><p><strong>Referencia:</strong> <?= htmlspecialchars($pedido['referencia']) ?></p><?php endif; ?>
                            <p><strong>Total:</strong> $<?= number_format($pedido['total'], 0, ',', '.') ?></p>
                            <p><strong>Pago:</strong> <?= htmlspecialchars(str_replace('Método de pago: ', '', $pedido['observaciones'])) ?></p>
                            <form action="/controller/domiciliario.php" method="POST" data-confirmacion="¿Deseas aceptar este pedido para entregarlo?"><input type="hidden" name="accion" value="aceptar"><input type="hidden" name="pedido_id" value="<?= (int) $pedido['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_domiciliario']) ?>"><button type="submit" class="btn-admin">Aceptar pedido</button></form>
                        </article>
                    <?php endforeach; ?>
                </div><?php else: ?><p class="mensaje-vacio">No hay pedidos pendientes por aceptar.</p><?php endif; ?>
            </section>
            <section class="entregas-seccion">
                <h2>Mis pedidos en camino</h2>
                <?php if (!empty($enCamino)): ?><div class="entregas-grid">
                    <?php foreach ($enCamino as $pedido): ?>
                        <article class="tarjeta-entrega">
                            <h3>Pedido #<?= (int) $pedido['id'] ?></h3>
                            <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['nombre']) ?> <?= htmlspecialchars($pedido['apellido']) ?></p>
                            <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
                            <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?>, <?= htmlspecialchars($pedido['barrio']) ?>, <?= htmlspecialchars($pedido['ciudad']) ?></p>
                            <p><strong>Contacto de entrega:</strong> <?= htmlspecialchars($pedido['telefono_contacto']) ?></p>
                            <?php if ($pedido['referencia']): ?><p><strong>Referencia:</strong> <?= htmlspecialchars($pedido['referencia']) ?></p><?php endif; ?>
                            <p><strong>Total:</strong> $<?= number_format($pedido['total'], 0, ',', '.') ?></p>
                            <form action="/controller/domiciliario.php" method="POST" data-confirmacion="¿Confirmas que el pedido fue entregado al cliente?"><input type="hidden" name="accion" value="finalizar"><input type="hidden" name="pedido_id" value="<?= (int) $pedido['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_domiciliario']) ?>"><button type="submit" class="btn-primary">Marcar como entregado</button></form>
                        </article>
                    <?php endforeach; ?>
                </div><?php else: ?><p class="mensaje-vacio">No tienes pedidos en camino.</p><?php endif; ?>
            </section>
            <div class="cerrar-entregas"><a href="/controller/cerrar_sesion.php" class="btn-cancelar">Cerrar sesión</a></div>
        </main>
    </div>
</body>
</html>
