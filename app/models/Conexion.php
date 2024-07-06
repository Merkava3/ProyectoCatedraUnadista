<?php
namespace Modelo;

class Conexion {
    private $server_name = "localhost";
    private $username = "servidor";
    private $password = "";
    private $database = "catedra";
    private $conexion;
    private static $instancia = null;

    private function __construct() {
        $this->conexion = $this->crearConexion();
    }

    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    private function crearConexion() {
        $conn = mysqli_connect($this->server_name, $this->username, $this->password, $this->database);

        if (!$conn) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        return $conn;
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function cerrarConexion() {
        if ($this->conexion) {
            mysqli_close($this->conexion);
            $this->conexion = null;
        }
    }

    private function __clone() { }
    public function __wakeup() { }
}
?>
