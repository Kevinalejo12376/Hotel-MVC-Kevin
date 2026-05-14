<?php

require_once 'conexion.php';

class Usuario
{
    private $conexionObj;
    private $conexion;

    public function __construct()
    {
        $this->conexionObj = new conexion();
        $this->conexionObj->conectar();
        $this->conexion = $this->conexionObj->getConexion();
    }

    // Buscar usuario por email
    public function obtenerPorEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error de preparación: ' . $this->conexion->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT u.*, t.tipo as tipo_documento_nombre 
                FROM usuarios u 
                LEFT JOIN tipos_documento t ON u.tipo_documento_id = t.id 
                WHERE u.id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error de preparación: ' . $this->conexion->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Verificar si el email ya existe
    public function emailExiste($email)
    {
        $sql = "SELECT email FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Verificar si el documento ya existe
    public function documentoExiste($documento)
    {
        $sql = "SELECT documento FROM usuarios WHERE documento = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $documento);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Guardar nuevo usuario en BD
    public function registrar($datos)
    {
        $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, tipo_documento_id, documento, `password`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        
        if (!$stmt) {
            throw new Exception('Error de preparación: ' . $this->conexion->error);
        }

        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        $stmt->bind_param(
            'ssssiss',
            $datos['nombre'],
            $datos['apellido'],
            $datos['email'],
            $datos['telefono'],
            $datos['tipo_documento_id'],
            $datos['documento'],
            $passwordHash
        );

        if (!$stmt->execute()) {
            throw new Exception('Error al guardar: ' . $stmt->error);
        }

        return true;
    }

    // Validar contraseña contra su hash
    public function verificarPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function __destruct()
    {
        $this->conexionObj->cerrar();
    }
}

?>
