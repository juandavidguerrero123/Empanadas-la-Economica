<?php

class Producto
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM productos ORDER BY id DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $descripcion, $precio, $imagen, $estado)
    {
        $sql = "INSERT INTO productos
                (nombre, descripcion, precio, imagen, estado, fecha_creacion)
                VALUES
                (:nombre, :descripcion, :precio, :imagen, :estado, :fecha_creacion)";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':imagen' => $imagen,
            ':estado' => $estado,
            ':fecha_creacion' => date('Y-m-d')
        ]);

        return true;
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM productos WHERE id = :id";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':id' => $id
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $imagen, $estado)
    {
        $sql = "UPDATE productos
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    imagen = :imagen,
                    estado = :estado
                WHERE id = :id";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':imagen' => $imagen,
            ':estado' => $estado
        ]);

        return true;
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM productos WHERE id = :id";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':id' => $id
        ]);

        return true;
    }
}