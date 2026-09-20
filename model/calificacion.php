<?php

class Calificacion
{
    private $conexion;

    public function __construct($conexion) { $this->conexion = $conexion; }

    public function obtenerPedidosEntregadosSinCalificar($usuarioId)
    {
        $sql = "SELECT p.id, p.fecha_pedido, p.total
                FROM pedidos p
                LEFT JOIN calificaciones c ON c.pedido_id = p.id
                WHERE p.usuario_id = :usuario_id
                    AND p.estado = 'Entregado'
                    AND c.id IS NULL
                ORDER BY p.id DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':usuario_id' => $usuarioId]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($pedidoId, $usuarioId, $puntuacion, $comentario)
    {
        $consultaPedido = $this->conexion->prepare(
            "SELECT p.id
            FROM pedidos p
            LEFT JOIN calificaciones c ON c.pedido_id = p.id
            WHERE p.id = :pedido_id
                AND p.usuario_id = :usuario_id
                AND p.estado = 'Entregado'
                AND c.id IS NULL"
        );
        $consultaPedido->execute([':pedido_id' => $pedidoId, ':usuario_id' => $usuarioId]);

        if (!$consultaPedido->fetch()) {
            return false;
        }

        $consulta = $this->conexion->prepare(
            "INSERT INTO calificaciones (pedido_id, usuario_id, calificacion, comentario, fecha)
            VALUES (:pedido_id, :usuario_id, :calificacion, :comentario, CURDATE())"
        );
        return $consulta->execute([
            ':pedido_id' => $pedidoId,
            ':usuario_id' => $usuarioId,
            ':calificacion' => $puntuacion,
            ':comentario' => $comentario !== '' ? $comentario : null
        ]);
    }

    public function obtenerTodas()
    {
        $sql = "SELECT c.id, c.calificacion, c.comentario, c.fecha,
                    p.id AS pedido_id,
                    u.nombre, u.apellido, u.correo,
                    d.nombre AS domiciliario_nombre, d.apellido AS domiciliario_apellido
                FROM calificaciones c
                INNER JOIN pedidos p ON p.id = c.pedido_id
                INNER JOIN usuarios u ON u.id = c.usuario_id
                LEFT JOIN asignaciones_domicilio a ON a.pedido_id = p.id
                LEFT JOIN usuarios d ON d.id = a.domiciliario_id
                ORDER BY c.id DESC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
