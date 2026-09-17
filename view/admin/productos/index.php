<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Productos - Administrador</title>

    <link rel="stylesheet" href="/view/css/admin.css">
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
                <h1>Productos</h1>
                <p>Administración de productos</p>
            </div>

        </header>

        <main class="admin-content">

            <div class="admin-section-header">

                <h2>Listado de productos</h2>

                <div>

                    <a
                        href="/view/admin/index.php"
                        class="btn-cancelar">
                        Volver al panel
                    </a>

                    <a
                        href="/view/admin/productos/crear.php"
                        class="btn-admin">
                        + Agregar producto
                    </a>

                </div>

            </div>

            <div class="table-container">

                <table class="productos-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Fecha de creación</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($productos)): ?>

                            <?php foreach ($productos as $producto): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($producto['id']) ?>
                                    </td>

                                    <td>

                                        <?php if (!empty($producto['imagen'])): ?>

                                            <img
                                                src="/assets/imagenes/<?= htmlspecialchars($producto['imagen']) ?>"
                                                alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                class="producto-imagen">

                                        <?php else: ?>

                                            Sin imagen

                                        <?php endif; ?>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars($producto['nombre']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($producto['descripcion']) ?>
                                    </td>

                                    <td>
                                        $<?= number_format($producto['precio'], 0, ',', '.') ?>
                                    </td>

                                    <td>

                                        <?php if ($producto['estado'] == 1): ?>

                                            <span class="estado-activo">
                                                Activo
                                            </span>

                                        <?php else: ?>

                                            <span class="estado-inactivo">
                                                Inactivo
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars($producto['fecha_creacion']) ?>
                                    </td>

                                    <td>

                                        <a
                                            href="/view/admin/productos/editar.php?id=<?= $producto['id'] ?>"
                                            class="btn-editar">
                                            Editar
                                        </a>

                                        <form
                                            action="/controller/productos.php"
                                            method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('¿Está seguro de que desea eliminar este producto?');">

                                            <input
                                                type="hidden"
                                                name="accion"
                                                value="eliminar">

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= htmlspecialchars($producto['id']) ?>">

                                            <button
                                                type="submit"
                                                class="btn-eliminar">
                                                Eliminar
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="8">
                                    No hay productos registrados.
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