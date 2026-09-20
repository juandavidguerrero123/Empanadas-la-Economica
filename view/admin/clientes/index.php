<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Clientes - Administrador</title>

    <link rel="stylesheet" href="/view/css/admin.css">
    <script src="/assets/js/app.js" defer></script>
</head>

<body>

    <div class="admin-container">

        <header class="admin-header">

            <div class="admin-logo">
                <img
                    src="/assets/imagenes/Logo.jpg"
                    alt="Empanadas La Económica">
            </div>

            <div>
                <h1>Clientes</h1>
                <p>Administración de clientes registrados</p>
            </div>

        </header>

        <main class="admin-content">

            <?php if (($_GET['mensaje'] ?? '') === 'estado_actualizado'): ?>
                <div
                    hidden
                    data-swal="success"
                    data-swal-titulo="Cliente actualizado"
                    data-parametros-url="mensaje">
                    El estado del cliente fue actualizado correctamente.
                </div>
            <?php endif; ?>

            <div class="admin-section-header">

                <h2>Listado de clientes</h2>

                <a
                    href="/view/admin/index.php"
                    class="btn-cancelar">
                    Volver al panel
                </a>

            </div>

            <form action="/controller/clientes.php" method="GET" class="buscador-clientes">

                <label for="buscar">Buscar cliente</label>

                <input
                    type="search"
                    id="buscar"
                    name="buscar"
                    value="<?= htmlspecialchars($busqueda) ?>"
                    placeholder="Nombre, correo, teléfono o documento">

                <button type="submit" class="btn-admin">Buscar</button>

                <?php if ($busqueda !== ''): ?>
                    <a href="/controller/clientes.php" class="btn-cancelar">Limpiar</a>
                <?php endif; ?>

            </form>

            <div class="table-container">

                <table class="productos-table clientes-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Documento</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Fecha de registro</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($clientes)): ?>

                            <?php foreach ($clientes as $cliente): ?>

                                <tr>
                                    <td><?= htmlspecialchars($cliente['id']) ?></td>

                                    <td>
                                        <?= htmlspecialchars($cliente['nombre']) ?>
                                        <?= htmlspecialchars($cliente['apellido']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($cliente['tipo_documento']) ?>
                                        <?= htmlspecialchars($cliente['numero_documento']) ?>
                                    </td>

                                    <td><?= htmlspecialchars($cliente['correo']) ?></td>
                                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>

                                    <td>
                                        <?php if ($cliente['estado'] == 1): ?>

                                            <span class="estado-activo">Activo</span>

                                        <?php else: ?>

                                            <span class="estado-inactivo">Inactivo</span>

                                        <?php endif; ?>
                                    </td>

                                    <td><?= htmlspecialchars($cliente['fecha_registro']) ?></td>

                                    <td>
                                        <a
                                            href="/controller/clientes.php?accion=detalle&id=<?= (int) $cliente['id'] ?>"
                                            class="btn-editar">
                                            Ver direcciones
                                        </a>

                                        <form
                                            action="/controller/clientes.php"
                                            method="POST"
                                            style="display: inline;"
                                            data-confirmacion="¿Desea cambiar el estado de este cliente?">

                                            <input type="hidden" name="id" value="<?= (int) $cliente['id'] ?>">
                                            <input type="hidden" name="estado" value="<?= $cliente['estado'] == 1 ? '0' : '1' ?>">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_clientes']) ?>">

                                            <button
                                                type="submit"
                                                class="<?= $cliente['estado'] == 1 ? 'btn-eliminar' : 'btn-admin' ?>">
                                                <?= $cliente['estado'] == 1 ? 'Inactivar' : 'Activar' ?>
                                            </button>

                                        </form>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8">
                                    <?= $busqueda !== '' ? 'No se encontraron clientes con esa búsqueda.' : 'No hay clientes registrados.' ?>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </main>

    </div>

</body>

</html>
