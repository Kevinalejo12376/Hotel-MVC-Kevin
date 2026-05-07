<?php
session_start();

require_once 'config/config.php';
require_once 'controller/BaseController.php';
require_once 'controller/LoginController.php';
require_once 'controller/RegisterController.php';
require_once 'controller/ReservasController.php';
require_once 'controller/HabitacionesController.php';

$baseController = new BaseController();
$loginController = new LoginController();
$registerController = new RegisterController();
$reservasController = new ReservasController();
$habitacionesController = new HabitacionesController();


if (isset($_GET['action'])) {

    // FORMULARIO DE REGISTRO
    if ($_GET['action'] == 'getFormRegisterUser') {
        $baseController->verPagina('views/html/auth/register.php');
    }

    // PROCESAR REGISTRO
    if ($_GET['action'] == 'registerUser') {
        $registerController->registrar();
    }

    // FORMULARIO DE LOGIN
    if ($_GET['action'] == 'getFormLoginUser') {
        $baseController->verPagina('views/html/auth/login.php');
    }

    // PROCESAR LOGIN
    if ($_GET['action'] == 'loginUser') {
        $loginController->login();
    }

    // PÁGINA DE INICIO EXITOSO (DESPUÉS DE LOGIN)
    if ($_GET['action'] == 'getFormInicioExitoso') {
        $baseController->verPagina('views/html/home2.php');
    }

    if ($_GET['action'] == 'getFormInicioExitosoServicios') {
        $baseController->getFormInicioExitosoSecciones('index.php?action=getFormInicioExitoso#servicios');
    }

    if ($_GET['action'] == 'getFormInicioExitosoHabitaciones') {
        $baseController->getFormInicioExitosoSecciones('index.php?action=getFormInicioExitoso#habitaciones');
    }
    if ($_GET['action'] == 'getFormInicioExitosoReservas') {
        $baseController->verPagina('views/html/reservations.php');
    }

    if ($_GET['action'] == 'reservarHabitacion') {
        $reservasController->reservarHabitacion();
    }

    if ($_GET['action'] == 'actualizarReserva') {
        $reservasController->actualizarReservaAjax();
    }

    if ($_GET['action'] == 'eliminarReserva' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $reservasController->eliminarReservaAjax();
    }

    if($_GET['action'] == 'reporteReserva'){
        $reservasController->generarReporteReserva();
    }

    if($_GET['action'] == 'reporteGeneral'){
        $reservasController->generarReporteGeneral();
    }

    // CERRAR SESIÓN
    if ($_GET['action'] == 'cerrarSesion') {
        $loginController->cerrarSesion();
    }
    if ($_GET['action'] == 'reservas') {
        $baseController->verPagina('views/html/reservations.php');
    }

    if ($_GET['action'] == 'getReservations') {
        $baseController->verPagina('views/html/reservations.php');
    }

    if ($_GET['action'] == 'getRoomsByType') {
        $reservasController->getRoomsByType();
    }

} else {
    // PÁGINA DE INICIO (SIN SESIÓN)
    $baseController->verPagina('views/html/home.php');
}

?>