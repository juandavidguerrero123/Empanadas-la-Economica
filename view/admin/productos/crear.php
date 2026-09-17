<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agregar producto - Administrador</title>

    <link rel="stylesheet" href="/view/css/admin.css">
</head>

<body>

    <div class="admin-container">

        <header class="admin-header">

            <div class="admin-logo">
                <img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica">
            </div>

            <div>
                <h1>Agregar producto</h1>
                <p>Registrar un nuevo producto</p>
            </div>

        </header>

        <main class="admin-content">

            <div class="formulario-producto">

                <h2>Información del producto</h2>

                <form action="/controller/productos.php" method="POST" enctype="multipart/form-data">

                    <div class="campo-formulario">

                        <label for="nombre">
                            Nombre del producto
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            maxlength="100"
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
                            required></textarea>

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
                            required>

                    </div>

                    <div class="campo-formulario">

                        <label for="imagen">
                            Imagen del producto
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

                        <select id="estado" name="estado" required>

                            <option value="1">
                                Activo
                            </option>

                            <option value="0">
                                Inactivo
                            </option>

                        </select>

                    </div>

                    <div class="botones-formulario">

                        <button
                            type="submit"
                            class="btn-admin">
                            Guardar producto
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