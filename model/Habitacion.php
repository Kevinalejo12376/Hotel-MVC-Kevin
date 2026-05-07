<?php
require_once 'conexion.php';

class Habitacion {
    public static function obtenerHabitaciones() {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT h.*, c.nombre AS categoria_nombre FROM habitaciones h JOIN categorias c ON h.id_categoria = c.id";
        $result = $conexion->query($sql);
        $habitaciones = [];
        while ($row = $result->fetch_assoc()) {
            $habitaciones[] = $row;
        }
        $conexion->cerrar();
        return $habitaciones;
    }

    public static function obtenerCategorias() {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT DISTINCT c.id, c.nombre FROM categorias c JOIN habitaciones h ON h.id_categoria = c.id";
        $result = $conexion->query($sql);
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        $conexion->cerrar();
        return $categorias;
    }
}
?>