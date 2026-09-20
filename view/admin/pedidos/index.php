<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Administrador</title>
    <link rel="stylesheet" href="/view/css/admin.css">
</head>

<body>

    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-logo">
                <img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica">
            </div>
            <div>
                <h1>Pedidos</h1>
                <p>Seguimiento de entregas y domiciliarios</p>
            </div>
        </header>

        <main class="admin-content">
            <div class="admin-section-header">
                <h2>Control de pedidos</h2>
                <a href="/view/admin/index.php" class="btn-cancelar">Volver al panel</a>
            </div>

            <?php
            $secciones = [
                ['titulo' => 'Pendientes de entrega', 'pedidos' => $pendientes, 'clase' => 'pedido-pendiente'],
                ['titulo' => 'Pedidos en camino', 'pedidos' => $enCamino, 'clase' => 'pedido-camino'],
                ['titulo' => 'Pedidos entregados', 'pedidos' => $entregados, 'clase' => 'pedido-entregado']
            ];
            ?>

            <?php foreach ($secciones as $seccion): ?>
                <section class="pedidos-seccion">
                    <h2><?= $seccion['titulo'] ?> <span class="contador-pedidos"><?= count($seccion['pedidos']) ?></span></h2>

                    <div class="table-container">
                        <table class="productos-table pedidos-table">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Dirección</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Domiciliario</th>
                                    <th>Fechas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($seccion['pedidos'])): ?>
                                    <?php foreach ($seccion['pedidos'] as $pedido): ?>
                                        <tr>
                                            <td>#<?= (int) $pedido['id'] ?></td>
                                            <td><?= htmlspecialchars($pedido['cliente_nombre']) ?> <?= htmlspecialchars($pedido['cliente_apellido']) ?></td>
                                            <td>
                                                <?php if ($pedido['direccion']): ?>
                                                    <?= htmlspecialchars($pedido['direccion']) ?><br>
                                                    <?= htmlspecialchars($pedido['barrio']) ?>, <?= htmlspecialchars($pedido['ciudad']) ?>
                                                <?php else: ?>
                                                    Dirección no disponible
                                                <?php endif; ?>
                                            </td>
                                            <td>$<?= number_format($pedido['total'], 0, ',', '.') ?></td>
                                            <td><span class="estado-pedido <?= $seccion['clase'] ?>"><?= htmlspecialchars($pedido['estado']) ?></span></td>
                                            <td>
                                                <?php if ($pedido['domiciliario_nombre']): ?>
                                                    <?= htmlspecialchars($pedido['domiciliario_nombre']) ?> <?= htmlspecialchars($pedido['domiciliario_apellido']) ?>
                                                <?php else: ?>
                                                    Sin asignar
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong>Pedido:</strong> <?= htmlspecialchars($pedido['fecha_pedido']) ?><br>
                                                <?php if ($pedido['fecha_asignacion']): ?><strong>Asignado:</strong> <?= htmlspecialchars($pedido['fecha_asignacion']) ?><br><?php endif; ?>
                                                <?php if ($pedido['fecha_entrega']): ?><strong>Entregado:</strong> <?= htmlspecialchars($pedido['fecha_entrega']) ?><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7">No hay pedidos en este estado.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endforeach; ?>
        </main>
    </div>

</body>
</html>
