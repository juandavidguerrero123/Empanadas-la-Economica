<?php

class Contacto
{
    private $conexion;

    public function __construct($conexion) { $this->conexion = $conexion; }

    public function crear($nombre, $correo, $mensaje)
    {
        $consulta = $this->conexion->prepare(
            "INSERT INTO contactos (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)"
        );
        return $consulta->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':mensaje' => $mensaje
        ]);
    }

    public function obtenerTodos()
    {
        return $this->conexion->query(
            "SELECT id, nombre, correo, mensaje, fecha_envio FROM contactos ORDER BY id DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
