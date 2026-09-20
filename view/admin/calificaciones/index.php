<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones - Administrador</title>
    <link rel="stylesheet" href="/view/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header"><div class="admin-logo"><img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica"></div><div><h1>Calificaciones</h1><p>Opiniones de los clientes sobre el servicio.</p></div></header>
        <main class="admin-content">
            <div class="admin-section-header"><h2>Opiniones recibidas</h2><a href="/view/admin/index.php" class="btn-cancelar">Volver al panel</a></div>
            <div class="table-container"><table class="productos-table"><thead><tr><th>Pedido</th><th>Cliente</th><th>Calificación</th><th>Comentario</th><th>Domiciliario</th><th>Fecha</th></tr></thead><tbody>
                <?php if (!empty($calificaciones)): ?>
                    <?php foreach ($calificaciones as $calificacion): ?><tr><td>#<?= (int) $calificacion['pedido_id'] ?></td><td><?= htmlspecialchars($calificacion['nombre']) ?> <?= htmlspecialchars($calificacion['apellido']) ?><br><small><?= htmlspecialchars($calificacion['correo']) ?></small></td><td><span class="estrellas-calificacion" aria-label="<?= (int) $calificacion['calificacion'] ?> de 5 estrellas"><?= str_repeat('★', (int) $calificacion['calificacion']) ?><span><?= str_repeat('☆', 5 - (int) $calificacion['calificacion']) ?></span></span></td><td><?= $calificacion['comentario'] ? htmlspecialchars($calificacion['comentario']) : 'Sin comentario' ?></td><td><?= $calificacion['domiciliario_nombre'] ? htmlspecialchars($calificacion['domiciliario_nombre'] . ' ' . $calificacion['domiciliario_apellido']) : 'No disponible' ?></td><td><?= htmlspecialchars($calificacion['fecha']) ?></td></tr><?php endforeach; ?>
                <?php else: ?><tr><td colspan="6">Aún no hay calificaciones registradas.</td></tr><?php endif; ?>
            </tbody></table></div>
        </main>
    </div>
</body>
</html>
