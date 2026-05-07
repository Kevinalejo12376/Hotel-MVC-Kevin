<?php

require_once 'model/Usuario.php';

class LoginController
{
    public function login()
    {
        $errors = [];

        $Email = trim($_POST['email'] ?? '');
        $Contraseña = $_POST['password'] ?? '';

        if (empty($Email)) {
            $errors['email'] = "El email es obligatorio";
        }

        if (empty($Contraseña)) {
            $errors['password'] = "La contraseña es obligatoria";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['datos'] = ['email' => $Email];
            header("Location: index.php?action=getFormLoginUser");
            exit;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->obtenerPorEmail($Email);

        if ($usuario && $usuarioModel->verificarPassword($Contraseña, $usuario['password'])) {

            $_SESSION['usuario'] = $usuario;

            header("Location: index.php?action=getFormInicioExitoso");
            exit;

        } else {
            $_SESSION['errors']['general'] = "Credenciales incorrectas";
            header("Location: index.php?action=getFormLoginUser");
            exit;
        }
    }

    // Destruir sesión y redirigir
    public function cerrarSesion()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php");
        exit;
    }
}

?>