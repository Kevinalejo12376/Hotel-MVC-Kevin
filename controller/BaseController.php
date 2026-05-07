<?php

require_once 'model/TipoDocumento.php';

class BaseController
{
    // Mostrar página con datos de sesión y tipos de documento
    public function verPagina($pagina)
    {
        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? [];
        $success = $_SESSION['success'] ?? '';

        $tipoDocumentoModel = new TipoDocumento();
        $_SESSION['documentTypes'] = $tipoDocumentoModel->obtenerTodos();
        include_once $pagina;
    }

    public function getFormInicioExitosoSecciones($secciones){
        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? [];
        $success = $_SESSION['success'] ?? '';

        header('Location: ' . $secciones);
    }

    public function eliminarReserva() {
        ReservasController::eliminarReservaAjax();
    }
}

?>
