<?php

class Cliente
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodos($busqueda = '')
    {
        $sql = "SELECT
                    id,
                    nombre,
                    apellido,
                    tipo_documento,
                    numero_documento,
                    correo,
                    telefono,
                    estado,
                    fecha_registro
                FROM usuarios
                WHERE rol_id = :rol_id";

        $parametros = [
            ':rol_id' => 2
        ];

        if ($busqueda !== '') {
            $sql .= " AND (
                        nombre LIKE :busqueda
                        OR apellido LIKE :busqueda
                        OR correo LIKE :busqueda
                        OR telefono LIKE :busqueda
                        OR numero_documento LIKE :busqueda
                    )";

            $parametros[':busqueda'] = '%' . $busqueda . '%';
        }

        $sql .= "
                ORDER BY id DESC";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute($parametros);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT id, nombre, apellido, tipo_documento, numero_documento,
                    correo, telefono, estado, fecha_registro
                FROM usuarios
                WHERE id = :id AND rol_id = :rol_id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':id' => $id, ':rol_id' => 2]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDirecciones($usuarioId)
    {
        $sql = "SELECT direccion, barrio, ciudad, referencia, telefono_contacto, estado
                FROM domicilios
                WHERE usuario_id = :usuario_id
                ORDER BY id DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':usuario_id' => $usuarioId]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstado($id, $estado)
    {
        $sql = "UPDATE usuarios
                SET estado = :estado
                WHERE id = :id AND rol_id = :rol_id";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ':id' => $id,
            ':estado' => $estado,
            ':rol_id' => 2
        ]);

        return $consulta->rowCount() > 0;
    }
}
