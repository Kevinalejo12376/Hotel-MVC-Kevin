<?php
require_once 'conexion.php';

class Reserva {
    public static function obtenerReservasPorUsuario($id_user) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT 
                    r.id, 
                    r.id_habitacion AS habitacion_id,
                    r.fecha_inicio AS entrada, 
                    r.fecha_final AS salida, 
                    r.num_personas AS personas, 
                    r.estado, 
                    r.precio AS total, 
                    h.num_habitacion AS habitacion, 
                    c.id AS categoria_id,
                    c.nombre AS tipo, 
                    m.nombre AS pago,
                    r.id_metodo_pago,
                    DATEDIFF(r.fecha_final, r.fecha_inicio) AS noches
                FROM reservas r 
                JOIN habitaciones h ON r.id_habitacion = h.id 
                JOIN categorias c ON h.id_categoria = c.id 
                LEFT JOIN metodos_pago m ON r.id_metodo_pago = m.id 
                WHERE r.id_user = $id_user";
        $result = $conexion->query($sql);
        $reservas = [];
        while ($row = $result->fetch_assoc()) {
            $reservas[] = $row;
        }
        $conexion->cerrar();
        return $reservas;
    }

    public static function obtenerReservaPorId($id, $id_user) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT 
                    r.id, 
                    r.id_habitacion AS habitacion_id,
                    r.fecha_inicio AS entrada, 
                    r.fecha_final AS salida, 
                    r.num_personas AS personas, 
                    r.estado, 
                    r.precio AS total, 
                    h.num_habitacion AS habitacion, 
                    c.id AS categoria_id,
                    c.nombre AS tipo, 
                    m.nombre AS pago,
                    r.id_metodo_pago,
                    DATEDIFF(r.fecha_final, r.fecha_inicio) AS noches
                FROM reservas r 
                JOIN habitaciones h ON r.id_habitacion = h.id 
                JOIN categorias c ON h.id_categoria = c.id 
                LEFT JOIN metodos_pago m ON r.id_metodo_pago = m.id 
                WHERE r.id = ? AND r.id_user = ?";
        $stmt = $conexion->getConexion()->prepare($sql);
        $stmt->bind_param("ii", $id, $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $reserva = $result->fetch_assoc();
        $stmt->close();
        $conexion->cerrar();
        return $reserva;
    }

    public static function obtenerMetodosPago() {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT id, nombre FROM metodos_pago";
        $result = $conexion->query($sql);
        $pagos = [];
        while ($row = $result->fetch_assoc()) {
            $pagos[] = $row;
        }
        $conexion->cerrar();
        if (empty($pagos)) {
            $pagos = [
                ['id' => 1, 'nombre' => 'Nequi'],
                ['id' => 2, 'nombre' => 'Daviplata'],
                ['id' => 3, 'nombre' => 'Bancolombia'],
            ];
        }
        return $pagos;
    }

    public static function guardarReserva($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "INSERT INTO reservas (id_user, id_habitacion, fecha_inicio, fecha_final, num_personas, estado, precio, id_metodo_pago, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conexion->getConexion()->prepare($sql);
        $stmt->bind_param("iissiiid", $data['id_user'], $data['id_habitacion'], $data['fecha_inicio'], $data['fecha_final'], $data['num_personas'], $data['estado'], $data['precio'], $data['id_metodo_pago']);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->cerrar();
        return $ok;
    }

    public static function actualizarReserva($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "UPDATE reservas SET id_habitacion = ?, fecha_inicio = ?, fecha_final = ?, num_personas = ?, id_metodo_pago = ?, precio = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conexion->getConexion()->prepare($sql);
        $stmt->bind_param("issiiid", $data['id_habitacion'], $data['fecha_inicio'], $data['fecha_fin'], $data['personas'], $data['id_metodo_pago'], $data['precio'], $data['id']);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->cerrar();
        return $ok;
    }

    public static function eliminarReserva($id) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "DELETE FROM reservas WHERE id = ?";
        $stmt = $conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->cerrar();
        return $ok;
    }
}
?>