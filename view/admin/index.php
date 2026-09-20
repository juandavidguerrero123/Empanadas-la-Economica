<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/* Verificar que haya una sesión iniciada */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../html/sesion.html?error=acceso");
    exit;
}

/* Verificar que sea administrador */
if ($_SESSION["rol_id"] != 1) {
    header("Location: ../html/menu.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel de Administrador - Empanadas La Económica</title>

    <link rel="icon" type="image/png" href="../../assets/imagenes/Logo.jpg">

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<header>

    <nav>

        <div class="bloque">

            <img
                src="../../assets/imagenes/Logo.jpg"
                alt="Logo Empanadas La Económica"
            >

            <div class="text-logo">

                <h1>Empanadas La Económica</h1>

                <p>Sabor casero, precio justo.</p>

            </div>

        </div>


        <div class="usuario-admin">

            <?= htmlspecialchars($_SESSION["nombre"]) ?>
            <?= htmlspecialchars($_SESSION["apellido"]) ?>

            <span>
                Administrador
            </span>

        </div>

    </nav>

</header>


<main class="contenedor-admin">

    <h1 class="titulo-admin">
        Panel del Administrador
    </h1>

    <p class="subtitulo-admin">
        Bienvenido al sistema de administración de Empanadas La Económica.
    </p>


    <section class="panel-admin">


        <!-- PRODUCTOS -->

        <article class="tarjeta-admin">

            <h2>🥟 Productos</h2>

            <p>
                Crear, consultar, modificar y eliminar
                los productos disponibles.
            </p>

            <a href="/controller/productos.php" class="btn-primary">
                Administrar productos
            </a>

        </article>


        <!-- CLIENTES -->

        <article class="tarjeta-admin">

            <h2>👤 Clientes</h2>

            <p>
                Consultar y administrar los usuarios
                registrados como clientes.
            </p>

            <a href="/controller/clientes.php" class="btn-primary">
                Administrar clientes
            </a>

        </article>


        <!-- PERSONAL -->

        <article class="tarjeta-admin">

            <h2>👥 Personal</h2>

            <p>
                Consultar y administrar las cuentas de
                administradores y domiciliarios.
            </p>

            <a href="/controller/personal.php" class="btn-primary">
                Administrar personal
            </a>

        </article>


    </section>


    <div style="text-align: center;">

        <a
            href="../../controller/cerrar_sesion.php"
            class="btn-cerrar"
        >
            Cerrar sesión
        </a>

    </div>

</main>


<footer>

    © 2025 Empanadas la Económica —
    Todos los derechos reservados

</footer>

</body>

</html>
