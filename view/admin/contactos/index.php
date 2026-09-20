<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes de contacto - Administrador</title>
    <link rel="stylesheet" href="/view/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header"><div class="admin-logo"><img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica"></div><div><h1>Mensajes de contacto</h1><p>Consultas recibidas desde el sitio web.</p></div></header>
        <main class="admin-content">
            <div class="admin-section-header"><h2>Mensajes recibidos</h2><a href="/view/admin/index.php" class="btn-cancelar">Volver al panel</a></div>
            <div class="table-container"><table class="productos-table contactos-table"><thead><tr><th>Fecha</th><th>Nombre</th><th>Correo</th><th>Mensaje</th></tr></thead><tbody>
                <?php if (!empty($contactos)): ?>
                    <?php foreach ($contactos as $contacto): ?><tr><td><?= htmlspecialchars($contacto['fecha_envio']) ?></td><td><?= htmlspecialchars($contacto['nombre']) ?></td><td><?= htmlspecialchars($contacto['correo']) ?></td><td class="mensaje-contacto"><?= nl2br(htmlspecialchars($contacto['mensaje'])) ?></td></tr><?php endforeach; ?>
                <?php else: ?><tr><td colspan="4">Aún no hay mensajes de contacto.</td></tr><?php endif; ?>
            </tbody></table></div>
        </main>
    </div>
</body>
</html>
