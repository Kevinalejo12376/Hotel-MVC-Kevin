<?php
require_once 'model/Reserva.php';

class ReservasController
{
    public static function obtenerReservasPorUsuario($id_user)
    {
        return Reserva::obtenerReservasPorUsuario($id_user);
    }

    public static function obtenerMetodosPago()
    {
        return Reserva::obtenerMetodosPago();
    }

    public static function guardarReserva($data)
    {
        return Reserva::guardarReserva($data);
    }

    public static function actualizarReserva($data)
    {
        return Reserva::actualizarReserva($data);
    }

    public static function reservarHabitacion()
    {
        if (!isset($_SESSION['usuario']['id'])) {
            header('Location: index.php?action=getFormLoginUser');
            exit;
        }
        $id_user = $_SESSION['usuario']['id'];
        $id_habitacion = $_POST['id_habitacion'];
        $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
        $fecha_final = $_POST['fecha_final'] ?? date('Y-m-d', strtotime('+1 day'));
        $num_personas = $_POST['num_personas'] ?? 1;
        $estado = 1;
        $precio = $_POST['precio'] ?? 0;
        $id_metodo_pago = $_POST['id_metodo_pago'] ?? 1;
        $data = [
            'id_user' => $id_user,
            'id_habitacion' => $id_habitacion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
            'num_personas' => $num_personas,
            'estado' => $estado,
            'precio' => $precio,
            'id_metodo_pago' => $id_metodo_pago
        ];
        $ok = self::guardarReserva($data);
        if ($ok) {
            $_SESSION['success'] = 'Reserva realizada correctamente';
        } else {
            $_SESSION['errors']['reserva'] = 'Error al guardar la reserva';
        }
        header('Location: index.php?action=getFormInicioExitosoReservas');
        exit;
    }

    public static function actualizarReservaAjax()
    {
        if (!isset($_SESSION['usuario']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
            exit;
        }
        $id = $_POST['reserva_id'];
        $id_habitacion = $_POST['id_habitacion'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $personas = $_POST['personas'];
        $precio = $_POST['precio'] ?? 0;
        $id_metodo_pago = $_POST['id_metodo_pago'] ?? 1;

        $data = [
            'id' => $id,
            'id_habitacion' => $id_habitacion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'personas' => $personas,
            'id_metodo_pago' => $id_metodo_pago,
            'precio' => $precio,
        ];
        $ok = self::actualizarReserva($data);
        echo json_encode(['status' => $ok ? 'success' : 'error']);
        exit;
    }

    public static function eliminarReservaAjax()
    {
        if (!isset($_SESSION['usuario']['id'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }
        $id = $_POST['id_reserva'];
        $ok = Reserva::eliminarReserva($id);
        echo json_encode(['success' => $ok, 'message' => $ok ? 'Reserva eliminada' : 'Error al eliminar']);
        exit;
    }
    public static function generarReporteReserva() {
        if (!isset($_SESSION['usuario']['id'])) {
            header('Location: index.php?action=getFormLoginUser');
            exit;
        }
        $id_reserva = $_GET['id_reserva'] ?? null;
        if (!$id_reserva) {
            echo "ID de reserva no proporcionado.";
            exit;
        }
        
        $id_user = $_SESSION['usuario']['id'];
        $reserva = Reserva::obtenerReservaPorId($id_reserva, $id_user);
        
        if (!$reserva) {
            echo "Reserva no encontrada o no tienes permisos para verla.";
            exit;
        }

        // Include the report generator file, passing the reservation data
        require_once 'report/reportes.php';
        exit;
    }

    public static function generarReporteGeneral() {
        if (!isset($_SESSION['usuario']['id'])) {
            header('Location: index.php?action=getFormLoginUser');
            exit;
        }
        
        $id_user = $_SESSION['usuario']['id'];
        // Obtenemos todas las reservas del usuario
        $reservas = Reserva::obtenerReservasPorUsuario($id_user);
        
        // Include the report generator file for excel, passing the reservations data
        require_once 'report/reportesGeneral.php';
        exit;
    }

    public static function getRoomsByType()
    {
        header('Content-Type: application/json; charset=utf-8');
        $tipoHabitacionId = isset($_GET['type_room_id']) ? (int) $_GET['type_room_id'] : 0;
        $habitaciones = [];

        try {
            $conexion = new Conexion();
            $conexion->conectar();
            $sql = "SELECT id, num_habitacion as number, precio, max_personas, descripcion FROM habitaciones WHERE id_categoria = ?";
            $stmt = $conexion->getConexion()->prepare($sql);
            if (!$stmt) {
                throw new Exception('No se pudo preparar la consulta');
            }
            $stmt->bind_param('i', $tipoHabitacionId);
            $stmt->execute();
            $resultado = $stmt->get_result();

            while ($fila = $resultado->fetch_assoc()) {
                $habitaciones[] = $fila;
            }
            $stmt->close();
            $conexion->cerrar();

            echo json_encode([
                'ok' => true,
                'data' => $habitaciones
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'ok' => false,
                'message' => 'Error al consultar habitaciones',
                'data' => []
            ]);
            exit;
        }
    }
    // ====== FIN DE LA IMPLEMENTACIÓN DEL CONTROLADOR SOLICITADA ======
}
?>