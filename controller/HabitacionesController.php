<?php
require_once 'model/Habitacion.php';

class HabitacionesController {
    public static function obtenerHabitaciones() {
        return Habitacion::obtenerHabitaciones();
    }

    public static function obtenerCategorias() {
        return Habitacion::obtenerCategorias();
    }
}
?>