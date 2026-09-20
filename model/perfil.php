<?php

class Perfil
{
    private $conexion;

    public function __construct($conexion) { $this->conexion = $conexion; }

    public function obtenerPorId($id)
    {
        $consulta = $this->conexion->prepare("SELECT id, nombre, apellido, tipo_documento, numero_documento, correo, telefono FROM usuarios WHERE id = :id");
        $consulta->execute([':id' => $id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function existeCorreoODocumentoDeOtroUsuario($id, $correo, $numeroDocumento)
    {
        $consulta = $this->conexion->prepare("SELECT id FROM usuarios WHERE (correo = :correo OR numero_documento = :numero_documento) AND id != :id");
        $consulta->execute([':id' => $id, ':correo' => $correo, ':numero_documento' => $numeroDocumento]);
        return (bool) $consulta->fetch();
    }

    public function actualizar($id, $datos)
    {
        $sql = "UPDATE usuarios
                SET nombre = :nombre, apellido = :apellido, tipo_documento = :tipo_documento,
                    numero_documento = :numero_documento, correo = :correo, telefono = :telefono";

        if ($datos['password'] !== '') {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE id = :id";
        $consulta = $this->conexion->prepare($sql);
        $parametros = [
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':tipo_documento' => $datos['tipo_documento'],
            ':numero_documento' => $datos['numero_documento'],
            ':correo' => $datos['correo'],
            ':telefono' => $datos['telefono']
        ];

        if ($datos['password'] !== '') {
            $parametros[':password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }

        return $consulta->execute($parametros);
    }
}
