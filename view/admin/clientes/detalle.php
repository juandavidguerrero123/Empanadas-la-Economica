<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de cliente - Administrador</title>
    <link rel="stylesheet" href="/view/css/admin.css">
</head>

<body>

    <div class="admin-container">

        <header class="admin-header">

            <div class="admin-logo">
                <img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica">
            </div>

            <div>
                <h1>Detalle de cliente</h1>
                <p>Información de contacto y direcciones registradas</p>
            </div>

        </header>

        <main class="admin-content">

            <div class="admin-section-header">

                <h2>
                    <?= htmlspecialchars($clienteSeleccionado['nombre']) ?>
                    <?= htmlspecialchars($clienteSeleccionado['apellido']) ?>
                </h2>

                <a href="/controller/clientes.php" class="btn-cancelar">
                    Volver a clientes
                </a>

            </div>

            <section class="formulario-producto cliente-detalle">

                <h2>Datos de contacto</h2>

                <div class="datos-cliente">
                    <p><strong>Documento:</strong> <?= htmlspecialchars($clienteSeleccionado['tipo_documento']) ?> <?= htmlspecialchars($clienteSeleccionado['numero_documento']) ?></p>
                    <p><strong>Correo:</strong> <?= htmlspecialchars($clienteSeleccionado['correo']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($clienteSeleccionado['telefono']) ?></p>
                    <p><strong>Registro:</strong> <?= htmlspecialchars($clienteSeleccionado['fecha_registro']) ?></p>
                </div>

            </section>

            <section class="direcciones-cliente">

                <h2>Direcciones registradas</h2>

                <?php if (!empty($direcciones)): ?>

                    <?php foreach ($direcciones as $direccion): ?>

                        <article class="tarjeta-direccion">
                            <p><strong>Dirección:</strong> <?= htmlspecialchars($direccion['direccion']) ?></p>
                            <p><strong>Barrio y ciudad:</strong> <?= htmlspecialchars($direccion['barrio']) ?>, <?= htmlspecialchars($direccion['ciudad']) ?></p>
                            <p><strong>Teléfono de contacto:</strong> <?= htmlspecialchars($direccion['telefono_contacto']) ?></p>

                            <?php if (!empty($direccion['referencia'])): ?>
                                <p><strong>Referencia:</strong> <?= htmlspecialchars($direccion['referencia']) ?></p>
                            <?php endif; ?>

                            <span class="<?= $direccion['estado'] == 1 ? 'estado-activo' : 'estado-inactivo' ?>">
                                <?= $direccion['estado'] == 1 ? 'Activa' : 'Inactiva' ?>
                            </span>
                        </article>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="mensaje-vacio">Este cliente no tiene direcciones registradas.</p>

                <?php endif; ?>

            </section>

        </main>

    </div>

</body>

</html>
