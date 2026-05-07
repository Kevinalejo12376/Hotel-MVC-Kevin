<?php
require_once("conexion.php");

class ReservationsModel
{
    public function getReservationsByUser($userId)
    {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT * FROM reservas WHERE usuario_id = ? ORDER BY fecha_inicio DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = $result->fetch_all(MYSQLI_ASSOC);
        $conexion->cerrar();
        return $reservations;
    }

    public function deleteReservation($reservationId, $userId)
    {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "DELETE FROM reservas WHERE id = ? AND usuario_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $reservationId, $userId);
        $stmt->execute();
        $conexion->cerrar();
    }

    // Add methods for insert, update, etc.
}
?>