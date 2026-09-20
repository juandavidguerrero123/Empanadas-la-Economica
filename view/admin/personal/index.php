<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal - Administrador</title>
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
                <h1>Personal</h1>
                <p>Administración de administradores y domiciliarios</p>
            </div>
        </header>

        <main class="admin-content">

            <?php if (isset($_GET['mensaje'], $_GET['tipo'])): ?>
                <div
                    hidden
                    data-swal="<?= $_GET['tipo'] === 'success' ? 'success' : 'error' ?>"
                    data-swal-titulo="<?= $_GET['tipo'] === 'success' ? 'Operación exitosa' : 'No fue posible completar la operación' ?>"
                    data-parametros-url="mensaje,tipo">
                    <?= htmlspecialchars($_GET['mensaje']) ?>
                </div>
            <?php endif; ?>

            <div class="admin-section-header">
                <h2>Gestión de personal</h2>
                <a href="/view/admin/index.php" class="btn-cancelar">Volver al panel</a>
            </div>

            <?php
            $secciones = [
                ['titulo' => 'Administradores', 'singular' => 'administrador', 'rol' => 1, 'personas' => $administradores],
                ['titulo' => 'Domiciliarios', 'singular' => 'domiciliario', 'rol' => 3, 'personas' => $domiciliarios]
            ];
            ?>

            <?php foreach ($secciones as $seccion): ?>
                <section class="personal-seccion">
                    <div class="admin-section-header">
                        <h2><?= $seccion['titulo'] ?></h2>
                        <a href="/controller/personal.php?accion=crear&rol=<?= $seccion['rol'] ?>" class="btn-admin">
                            + Agregar <?= $seccion['singular'] ?>
                        </a>
                    </div>

                    <div class="table-container">
                        <table class="productos-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre completo</th>
                                    <th>Documento</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($seccion['personas'])): ?>
                                    <?php foreach ($seccion['personas'] as $persona): ?>
                                        <tr>
                                            <td><?= (int) $persona['id'] ?></td>
                                            <td><?= htmlspecialchars($persona['nombre']) ?> <?= htmlspecialchars($persona['apellido']) ?></td>
                                            <td><?= htmlspecialchars($persona['tipo_documento']) ?> <?= htmlspecialchars($persona['numero_documento']) ?></td>
                                            <td><?= htmlspecialchars($persona['correo']) ?></td>
                                            <td><?= htmlspecialchars($persona['telefono']) ?></td>
                                            <td>
                                                <span class="<?= $persona['estado'] == 1 ? 'estado-activo' : 'estado-inactivo' ?>">
                                                    <?= $persona['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($seccion['rol'] === 1 && (int) $persona['id'] === (int) $_SESSION['usuario_id']): ?>
                                                    Cuenta actual
                                                <?php else: ?>
                                                    <form action="/controller/personal.php" method="POST" style="display: inline;" data-confirmacion="¿Desea cambiar el estado de esta cuenta?">
                                                        <input type="hidden" name="accion" value="cambiar_estado">
                                                        <input type="hidden" name="id" value="<?= (int) $persona['id'] ?>">
                                                        <input type="hidden" name="rol_id" value="<?= $seccion['rol'] ?>">
                                                        <input type="hidden" name="estado" value="<?= $persona['estado'] == 1 ? '0' : '1' ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_personal']) ?>">
                                                        <button type="submit" class="<?= $persona['estado'] == 1 ? 'btn-eliminar' : 'btn-admin' ?>">
                                                            <?= $persona['estado'] == 1 ? 'Inactivar' : 'Activar' ?>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7">No hay <?= strtolower($seccion['titulo']) ?> registrados.</td></tr>
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
