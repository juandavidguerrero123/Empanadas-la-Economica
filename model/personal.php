<?php

class Personal
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPorRol($rolId)
    {
        $sql = "SELECT id, nombre, apellido, tipo_documento, numero_documento,
                    correo, telefono, estado, fecha_registro
                FROM usuarios
                WHERE rol_id = :rol_id
                ORDER BY id DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':rol_id' => $rolId]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeCorreoODocumento($correo, $numeroDocumento)
    {
        $sql = "SELECT id FROM usuarios
                WHERE correo = :correo OR numero_documento = :numero_documento";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ':correo' => $correo,
            ':numero_documento' => $numeroDocumento
        ]);

        return (bool) $consulta->fetch();
    }

    public function crear($rolId, $nombre, $apellido, $tipoDocumento, $numeroDocumento, $correo, $password, $telefono)
    {
        $sql = "INSERT INTO usuarios
                (rol_id, nombre, apellido, tipo_documento, numero_documento,
                correo, password, telefono, estado, fecha_registro)
                VALUES
                (:rol_id, :nombre, :apellido, :tipo_documento, :numero_documento,
                :correo, :password, :telefono, 1, CURDATE())";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':rol_id' => $rolId,
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':tipo_documento' => $tipoDocumento,
            ':numero_documento' => $numeroDocumento,
            ':correo' => $correo,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':telefono' => $telefono
        ]);
    }

    public function cambiarEstado($id, $rolId, $estado)
    {
        $sql = "UPDATE usuarios
                SET estado = :estado
                WHERE id = :id AND rol_id = :rol_id";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            ':id' => $id,
            ':rol_id' => $rolId,
            ':estado' => $estado
        ]);
    }
}
