<?php

class Pedido
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPorEstado($estado)
    {
        $sql = "SELECT
                    p.id,
                    p.fecha_pedido,
                    p.estado,
                    p.total,
                    p.observaciones,
                    cliente.nombre AS cliente_nombre,
                    cliente.apellido AS cliente_apellido,
                    d.direccion,
                    d.barrio,
                    d.ciudad,
                    domiciliario.nombre AS domiciliario_nombre,
                    domiciliario.apellido AS domiciliario_apellido,
                    a.fecha_asignacion,
                    a.fecha_entrega
                FROM pedidos p
                INNER JOIN usuarios cliente ON cliente.id = p.usuario_id
                LEFT JOIN domicilios d ON d.id = p.domicilio_id
                LEFT JOIN asignaciones_domicilio a ON a.pedido_id = p.id
                LEFT JOIN usuarios domiciliario ON domiciliario.id = a.domiciliario_id
                WHERE p.estado = :estado
                ORDER BY p.id DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':estado' => $estado]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
