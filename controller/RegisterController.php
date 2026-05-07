<?php

require_once 'model/Usuario.php';

class RegisterController
{
    public function registrar()
    {  
        $errors = [];

        $Nombre = trim($_POST['nombre'] ?? '');
        $Apellido = trim($_POST['apellido'] ?? '');
        $Email = trim($_POST['email'] ?? '');
        $Telefono = trim($_POST['telefono'] ?? '');
        $TipoDocId = (int)($_POST['tipo_documento_id'] ?? 0);
        $Documento = trim($_POST['documento'] ?? '');
        $Contraseña1 = $_POST['password'] ?? '';
        $Contraseña2 = $_POST['confirm_password'] ?? '';

        // Guardar datos para devolverlos al form
        $_SESSION['datos'] = [
            'nombre' => $Nombre,
            'apellido' => $Apellido,
            'email' => $Email,
            'telefono' => $Telefono,
            'tipo_documento_id' => $TipoDocId,
            'documento' => $Documento
        ];

        // VALIDACIONES

        if (empty($Nombre)) {
            $errors['nombre'] = "El nombre es obligatorio";
        } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $Nombre)) {
            $errors['nombre'] = "El nombre solo debe contener letras";
        } elseif (mb_strlen($Nombre) < 3) {
            $errors['nombre'] = "El nombre debe tener al menos 3 caracteres";
        }
        if (empty($Apellido)) {
            $errors['apellido'] = "El apellido es obligatorio";
        } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $Apellido)) {
            $errors['apellido'] = "El apellido solo debe contener letras";
        }
        if (empty($TipoDocId)) {
            $errors['tipo_documento_id'] = "El tipo de documento es obligatorio";
        }
        if (empty($Documento)) {
            $errors['documento'] = "El número de documento es obligatorio";
        } elseif (!preg_match('/^\d+$/', str_replace(' ', '', $Documento))) {
            $errors['documento'] = "El número de documento solo debe contener números";
        }
        if (empty($Telefono)) {
            $errors['telefono'] = "El teléfono es obligatorio";
        } elseif (!preg_match('/^\d+$/', str_replace(' ', '', $Telefono))) {
            $errors['telefono'] = "El teléfono solo debe contener números";
        }

        if (empty($Email)) {
            $errors['email'] = "El email es obligatorio";
        } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email no válido";
        }

        if (empty($Contraseña1)) {
            $errors['password'] = "La contraseña es obligatoria";
        } elseif (mb_strlen($Contraseña1) < 8) {
            $errors['password'] = "Mínimo 8 caracteres";
        }

        if (empty($Contraseña2)) {
            $errors['confirm_password'] = "Confirma la contraseña";
        } elseif ($Contraseña1 !== $Contraseña2) {
            $errors['confirm_password'] = "No coinciden";
        }



        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;

            header("Location: index.php?action=getFormRegisterUser");
            exit;
        }

        // Validar email y documento únicos
        $usuarioModel = new Usuario();
        if ($usuarioModel->emailExiste($Email)) {
            $errors['email'] = "El email ya está registrado";
        }
        if ($usuarioModel->documentoExiste($Documento)) {
            $errors['documento'] = "El número de documento ya está registrado";
        }
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?action=getFormRegisterUser");
            exit;
        }

        // Registrar usuario
        $datos = [
            'nombre' => $Nombre,
            'apellido' => $Apellido,
            'email' => $Email,
            'telefono' => $Telefono,
            'tipo_documento_id' => $TipoDocId,
            'documento' => $Documento,
            'password' => $Contraseña1
        ];

        $usuarioModel->registrar($datos);

        $_SESSION['mensaje'] = "¡Usuario creado con éxito!";
        $_SESSION['tipo'] = "success";
        $_SESSION['datos'] = [];
        header("Location: index.php?action=getFormRegisterUser&success=1");
        exit;
    }
}

?>
