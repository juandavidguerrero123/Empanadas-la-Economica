<?php

require_once "../config/database.php";
require_once "../model/Producto.php";

$producto = new Producto($conexion);

function redirigirConErrorProducto($mensaje)
{
    header('Location: /controller/productos.php?mensaje=' . urlencode($mensaje));
    exit;
}

/*
|--------------------------------------------------------------------------
| ELIMINAR PRODUCTO
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['accion']) &&
    $_POST['accion'] === 'eliminar'
) {

    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        redirigirConErrorProducto('El producto seleccionado no es válido.');
    }

    $id = (int) $_POST['id'];

    // Obtener el producto antes de eliminarlo
    $productoActual = $producto->obtenerPorId($id);

    if (!$productoActual) {
        redirigirConErrorProducto('El producto seleccionado no existe.');
    }

    // Eliminar de la base de datos
    $producto->eliminar($id);

    // Eliminar la imagen asociada
    if (!empty($productoActual['imagen'])) {

        $rutaImagen = "../assets/imagenes/" . $productoActual['imagen'];

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }

    header("Location: /controller/productos.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| CREAR / ACTUALIZAR PRODUCTO
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = isset($_POST['id']) && $_POST['id'] !== ''
        ? (int) $_POST['id']
        : null;

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = $_POST['precio'];
    $estado = $_POST['estado'];

    /*
    |--------------------------------------------------------------------------
    | CREAR PRODUCTO
    |--------------------------------------------------------------------------
    */

    if ($id === null) {

        $imagen = null;

        /*
        |--------------------------------------------------------------------------
        | IMAGEN
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] === UPLOAD_ERR_OK
        ) {

            $archivo = $_FILES['imagen'];

            $tipoImagen = getimagesize($archivo['tmp_name']);

            if ($tipoImagen === false) {
                redirigirConErrorProducto('El archivo seleccionado no es una imagen válida.');
            }

            $extension = strtolower(
                pathinfo($archivo['name'], PATHINFO_EXTENSION)
            );

            $extensionesPermitidas = [
                'jpg',
                'jpeg',
                'png',
                'webp',
                'avif'
            ];

            if (!in_array($extension, $extensionesPermitidas)) {
                redirigirConErrorProducto('El formato de imagen no está permitido.');
            }

            $nombreImagen = uniqid('producto_', true) . '.' . $extension;

            $rutaImagen = "../assets/imagenes/" . $nombreImagen;

            if (!move_uploaded_file(
                $archivo['tmp_name'],
                $rutaImagen
            )) {
                redirigirConErrorProducto('No fue posible guardar la imagen.');
            }

            $imagen = $nombreImagen;
        }

        $producto->crear(
            $nombre,
            $descripcion,
            $precio,
            $imagen,
            $estado
        );

    } else {

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR PRODUCTO
        |--------------------------------------------------------------------------
        */

        $productoActual = $producto->obtenerPorId($id);

        if (!$productoActual) {
            redirigirConErrorProducto('El producto seleccionado no existe.');
        }

        // Mantener la imagen actual
        $imagen = $productoActual['imagen'];

        /*
        |--------------------------------------------------------------------------
        | NUEVA IMAGEN
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] === UPLOAD_ERR_OK
        ) {

            $archivo = $_FILES['imagen'];

            $tipoImagen = getimagesize($archivo['tmp_name']);

            if ($tipoImagen === false) {
                redirigirConErrorProducto('El archivo seleccionado no es una imagen válida.');
            }

            $extension = strtolower(
                pathinfo($archivo['name'], PATHINFO_EXTENSION)
            );

            $extensionesPermitidas = [
                'jpg',
                'jpeg',
                'png',
                'webp',
                'avif'
            ];

            if (!in_array($extension, $extensionesPermitidas)) {
                redirigirConErrorProducto('El formato de imagen no está permitido.');
            }

            $nombreImagen = uniqid('producto_', true) . '.' . $extension;

            $rutaImagen = "../assets/imagenes/" . $nombreImagen;

            if (!move_uploaded_file(
                $archivo['tmp_name'],
                $rutaImagen
            )) {
                redirigirConErrorProducto('No fue posible guardar la nueva imagen.');
            }

            $imagenAnterior = $imagen;
            $imagen = $nombreImagen;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR EN LA BASE DE DATOS
        |--------------------------------------------------------------------------
        */

        $producto->actualizar(
            $id,
            $nombre,
            $descripcion,
            $precio,
            $imagen,
            $estado
        );

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR IMAGEN ANTERIOR
        |--------------------------------------------------------------------------
        */

        if (
            isset($imagenAnterior) &&
            !empty($imagenAnterior)
        ) {

            $rutaImagenAnterior = "../assets/imagenes/" . $imagenAnterior;

            if (file_exists($rutaImagenAnterior)) {
                unlink($rutaImagenAnterior);
            }
        }
    }

    header("Location: /controller/productos.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| LISTAR PRODUCTOS
|--------------------------------------------------------------------------
*/

$productos = $producto->obtenerTodos();

require_once "../view/admin/productos/index.php";
