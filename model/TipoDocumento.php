<?php

require_once 'conexion.php';

class TipoDocumento
{
    private $conexionObj;
    private $conexion;

    public function __construct()
    {
        $this->conexionObj = new conexion();
        $this->conexionObj->conectar();
        $this->conexion = $this->conexionObj->getConexion();
    }

    // Traer todos los tipos de documento
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM tipos_documento";
        $result = $this->conexion->query($sql);
        
        if ($result === false) {
            throw new Exception('Query error: ' . $this->conexion->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function __destruct()
    {
        $this->conexionObj->cerrar();
    }
}

?>
