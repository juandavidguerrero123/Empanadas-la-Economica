<?php

require_once "../../../config/database.php";
require_once "../../../model/Producto.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Producto no válido.");
}

$id = (int) $_GET['id'];

$productoModelo = new Producto($conexion);

$producto = $productoModelo->obtenerPorId($id);

if (!$producto) {
    die("El producto no existe.");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar producto - Administrador</title>

    <link rel="stylesheet" href="/view/css/admin.css">
</head>

<body>

    <div class="admin-container">

        <header class="admin-header">

            <div class="admin-logo">
                <img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica">
            </div>

            <div>
                <h1>Editar producto</h1>
                <p>Modificar información del producto</p>
            </div>

        </header>

        <main class="admin-content">

            <h2 class="titulo-formulario">
                Información del producto
            </h2>

            <div class="formulario-producto">

                <form action="/controller/productos.php" method="POST" enctype="multipart/form-data">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= htmlspecialchars($producto['id']) ?>">

                    <div class="campo-formulario">

                        <label for="nombre">
                            Nombre del producto
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            maxlength="100"
                            value="<?= htmlspecialchars($producto['nombre']) ?>"
                            required>

                    </div>

                    <div class="campo-formulario">

                        <label for="descripcion">
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="5"
                            required><?= htmlspecialchars($producto['descripcion']) ?></textarea>

                    </div>

                    <div class="campo-formulario">

                        <label for="precio">
                            Precio
                        </label>

                        <input
                            type="number"
                            id="precio"
                            name="precio"
                            min="0"
                            step="1"
                            value="<?= htmlspecialchars($producto['precio']) ?>"
                            required>

                    </div>

                    <div class="campo-formulario">

                        <label>
                            Imagen actual
                        </label>

                        <?php if (!empty($producto['imagen'])): ?>

                            <img
                                src="/assets/imagenes/<?= htmlspecialchars($producto['imagen']) ?>"
                                alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                class="imagen-editar">

                        <?php else: ?>

                            <p>No hay imagen registrada.</p>

                        <?php endif; ?>

                    </div>

                    <div class="campo-formulario">

                        <label for="imagen">
                            Nueva imagen
                        </label>

                        <input
                            type="file"
                            id="imagen"
                            name="imagen"
                            accept="image/*">

                    </div>

                    <div class="campo-formulario">

                        <label for="estado">
                            Estado
                        </label>

                        <select
                            id="estado"
                            name="estado"
                            required>

                            <option
                                value="1"
                                <?= $producto['estado'] == 1 ? 'selected' : '' ?>>
                                Activo
                            </option>

                            <option
                                value="0"
                                <?= $producto['estado'] == 0 ? 'selected' : '' ?>>
                                Inactivo
                            </option>

                        </select>

                    </div>

                    <div class="botones-formulario">

                        <button
                            type="submit"
                            class="btn-admin">
                            Guardar cambios
                        </button>

                        <a
                            href="/controller/productos.php"
                            class="btn-cancelar">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>