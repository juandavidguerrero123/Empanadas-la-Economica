<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar <?= htmlspecialchars($nombreRol) ?> - Administrador</title>
    <link rel="stylesheet" href="/view/css/admin.css">
    <script src="/assets/js/app.js" defer></script>
</head>

<body>

    <div class="admin-container">

        <header class="admin-header">
            <div class="admin-logo">
                <img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica">
            </div>
            <div>
                <h1>Agregar <?= htmlspecialchars($nombreRol) ?></h1>
                <p>Registrar una cuenta de personal</p>
            </div>
        </header>

        <main class="admin-content">

            <?php if (isset($_GET['mensaje'])): ?>
                <div hidden data-swal="error" data-swal-titulo="Revisa la información" data-parametros-url="mensaje,tipo">
                    <?= htmlspecialchars($_GET['mensaje']) ?>
                </div>
            <?php endif; ?>

            <div class="formulario-producto">
                <h2>Información de <?= strtolower($nombreRol) ?></h2>

                <form action="/controller/personal.php" method="POST">
                    <input type="hidden" name="accion" value="crear">
                    <input type="hidden" name="rol_id" value="<?= $rolId ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_personal']) ?>">

                    <div class="campo-formulario">
                        <label for="nombre">Nombres</label>
                        <input type="text" id="nombre" name="nombre" maxlength="100" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="apellido">Apellidos</label>
                        <input type="text" id="apellido" name="apellido" maxlength="100" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="tipo_documento">Tipo de documento</label>
                        <select id="tipo_documento" name="tipo_documento" required>
                            <option value="">Seleccione una opción</option>
                            <option value="50">Cédula de ciudadanía</option>
                            <option value="20">Tarjeta de identidad</option>
                            <option value="60">Cédula de extranjería</option>
                            <option value="1">Pasaporte</option>
                        </select>
                    </div>

                    <div class="campo-formulario">
                        <label for="numero_documento">Número de documento</label>
                        <input type="text" id="numero_documento" name="numero_documento" inputmode="numeric" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" maxlength="150" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" maxlength="20" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="password">Contraseña temporal</label>
                        <input type="password" id="password" name="password" minlength="6" required>
                    </div>

                    <div class="botones-formulario">
                        <button type="submit" class="btn-admin">Guardar <?= htmlspecialchars($nombreRol) ?></button>
                        <a href="/controller/personal.php" class="btn-cancelar">Cancelar</a>
                    </div>
                </form>
            </div>

        </main>

    </div>

</body>

</html>
