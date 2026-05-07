<?php

class conexion
{
    private $sql; // query a la base de datos
    private $result; // resultado de la query
    private $filasAfectadas; // numero de filas afectadas por la query
    private $conexionDb;

    public function conectar()
    {
        $host = "localhost";
        $db = "hotel";
        $user = "root";
        $pass = "";

        try {
            $this->conexionDb = new mysqli($host, $user, $pass, $db);

            // Verificar conexión
            if ($this->conexionDb->connect_error) {
                throw new Exception("Error de conexión: " . $this->conexionDb->connect_error);
            }

            // Establecer charset
            $this->conexionDb->set_charset("utf8");

        } catch (Exception $e) {
            die($e->getMessage());
        }


    }
    public function query($sql) {
        $this->sql = $sql;
        $this->result = $this->conexionDb->query($sql);
        if ($this->result === false) {
            throw new Exception('Query error: ' . $this->conexionDb->error);
        }
        $this->filasAfectadas = $this->conexionDb->affected_rows;
        return $this->result;
    }

    public function getConexion(){
        return $this->conexionDb;
    }

    public function cerrar(){
        $this->conexionDb = null;
    }


}

?>
