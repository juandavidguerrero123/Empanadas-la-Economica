<?php

class Domiciliario
{
    private $conexion;

    public function __construct($conexion) { $this->conexion = $conexion; }

    public function obtenerPendientes()
    {
        $sql = "SELECT p.id, p.fecha_pedido, p.total, p.observaciones, u.nombre, u.apellido, u.telefono,
                    d.direccion, d.barrio, d.ciudad, d.referencia, d.telefono_contacto
                FROM pedidos p
                INNER JOIN usuarios u ON u.id = p.usuario_id
                INNER JOIN domicilios d ON d.id = p.domicilio_id
                LEFT JOIN asignaciones_domicilio a ON a.pedido_id = p.id
                WHERE a.id IS NULL AND p.estado IN ('Pendiente de asignación', 'En proceso de entrega')
                ORDER BY p.id DESC";
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEnCamino($domiciliarioId)
    {
        $sql = "SELECT p.id, p.total, p.observaciones, u.nombre, u.apellido, u.telefono,
                    d.direccion, d.barrio, d.ciudad, d.referencia, d.telefono_contacto
                FROM asignaciones_domicilio a
                INNER JOIN pedidos p ON p.id = a.pedido_id
                INNER JOIN usuarios u ON u.id = p.usuario_id
                INNER JOIN domicilios d ON d.id = p.domicilio_id
                WHERE a.domiciliario_id = :domiciliario_id AND a.estado = 'En camino'
                ORDER BY a.id DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':domiciliario_id' => $domiciliarioId]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aceptarPedido($pedidoId, $domiciliarioId)
    {
        $this->conexion->beginTransaction();
        try {
            $pedido = $this->conexion->prepare("SELECT id FROM pedidos WHERE id = :id AND estado IN ('Pendiente de asignación', 'En proceso de entrega') FOR UPDATE");
            $pedido->execute([':id' => $pedidoId]);
            if (!$pedido->fetch()) { throw new RuntimeException('El pedido ya no está disponible.'); }

            $asignacion = $this->conexion->prepare("INSERT INTO asignaciones_domicilio (pedido_id, domiciliario_id, fecha_asignacion, estado) VALUES (:pedido_id, :domiciliario_id, CURDATE(), 'En camino')");
            $asignacion->execute([':pedido_id' => $pedidoId, ':domiciliario_id' => $domiciliarioId]);
            $actualizar = $this->conexion->prepare("UPDATE pedidos SET estado = 'En camino' WHERE id = :id");
            $actualizar->execute([':id' => $pedidoId]);
            $this->conexion->commit();
            return [true, 'Pedido aceptado. Ya puedes realizar la entrega.'];
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) { $this->conexion->rollBack(); }
            return [false, $e->getCode() === '23000' ? 'El pedido acaba de ser aceptado por otro domiciliario.' : $e->getMessage()];
        }
    }

    public function finalizarPedido($pedidoId, $domiciliarioId)
    {
        $this->conexion->beginTransaction();
        try {
            $asignacion = $this->conexion->prepare("SELECT id FROM asignaciones_domicilio WHERE pedido_id = :pedido_id AND domiciliario_id = :domiciliario_id AND estado = 'En camino' FOR UPDATE");
            $asignacion->execute([':pedido_id' => $pedidoId, ':domiciliario_id' => $domiciliarioId]);
            if (!$asignacion->fetch()) { throw new RuntimeException('No tienes permiso para finalizar este pedido.'); }

            $actualizarAsignacion = $this->conexion->prepare("UPDATE asignaciones_domicilio SET estado = 'Entregado', fecha_entrega = CURDATE() WHERE pedido_id = :pedido_id");
            $actualizarAsignacion->execute([':pedido_id' => $pedidoId]);
            $actualizarPedido = $this->conexion->prepare("UPDATE pedidos SET estado = 'Entregado' WHERE id = :id");
            $actualizarPedido->execute([':id' => $pedidoId]);
            $this->conexion->commit();
            return [true, 'Pedido marcado como entregado.'];
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) { $this->conexion->rollBack(); }
            return [false, $e->getMessage()];
        }
    }
}
