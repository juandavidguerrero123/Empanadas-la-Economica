<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - Empanadas La Económica</title>
    <link rel="stylesheet" href="/view/css/admin.css">
    <script src="/assets/js/app.js" defer></script>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <div class="admin-logo"><img src="/assets/imagenes/Logo.jpg" alt="Empanadas La Económica"></div>
            <div><h1>Mi perfil</h1><p>Actualiza tus datos personales</p></div>
        </header>
        <main class="admin-content">
            <?php if (isset($_GET['mensaje'], $_GET['tipo'])): ?>
                <div hidden data-swal="<?= $_GET['tipo'] === 'success' ? 'success' : 'error' ?>" data-swal-titulo="<?= $_GET['tipo'] === 'success' ? 'Datos actualizados' : 'Revisa la información' ?>" data-parametros-url="mensaje,tipo"><?= htmlspecialchars($_GET['mensaje']) ?></div>
            <?php endif; ?>
            <div class="formulario-producto">
                <h2>Información personal</h2>
                <form action="/controller/perfil.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_perfil']) ?>">
                    <div class="campo-formulario"><label for="nombre">Nombres</label><input type="text" id="nombre" name="nombre" maxlength="100" value="<?= htmlspecialchars($perfil['nombre']) ?>" required></div>
                    <div class="campo-formulario"><label for="apellido">Apellidos</label><input type="text" id="apellido" name="apellido" maxlength="100" value="<?= htmlspecialchars($perfil['apellido']) ?>" required></div>
                    <div class="campo-formulario"><label for="tipo_documento">Tipo de documento</label><select id="tipo_documento" name="tipo_documento" required><option value="50" <?= $perfil['tipo_documento'] === '50' ? 'selected' : '' ?>>Cédula de ciudadanía</option><option value="20" <?= $perfil['tipo_documento'] === '20' ? 'selected' : '' ?>>Tarjeta de identidad</option><option value="60" <?= $perfil['tipo_documento'] === '60' ? 'selected' : '' ?>>Cédula de extranjería</option><option value="1" <?= $perfil['tipo_documento'] === '1' ? 'selected' : '' ?>>Pasaporte</option></select></div>
                    <div class="campo-formulario"><label for="numero_documento">Número de documento</label><input type="text" id="numero_documento" name="numero_documento" inputmode="numeric" value="<?= htmlspecialchars($perfil['numero_documento']) ?>" required></div>
                    <div class="campo-formulario"><label for="correo">Correo electrónico</label><input type="email" id="correo" name="correo" maxlength="150" value="<?= htmlspecialchars($perfil['correo']) ?>" required></div>
                    <div class="campo-formulario"><label for="telefono">Teléfono</label><input type="tel" id="telefono" name="telefono" maxlength="20" value="<?= htmlspecialchars($perfil['telefono']) ?>" required></div>
                    <div class="campo-formulario"><label for="password">Nueva contraseña <small>(opcional)</small></label><input type="password" id="password" name="password" minlength="6" placeholder="Déjala vacía para conservar la actual"></div>
                    <p class="nota-perfil">El rol de la cuenta no puede modificarse desde el perfil.</p>
                    <div class="botones-formulario"><button type="submit" class="btn-admin">Guardar cambios</button><a href="<?= $_SESSION['rol_id'] == 1 ? '/view/admin/index.php' : ($_SESSION['rol_id'] == 3 ? '/controller/domiciliario.php' : '/view/html/menu.php') ?>" class="btn-cancelar">Volver</a></div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
