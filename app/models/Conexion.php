<?php
namespace Models;

require_once __DIR__ . '/../config/config.php';

class Conexion {    
    private $server_name;
    private $username;
    private $password;
    private $database;
    private $conexion;
    private static $instancia = null;

    private function __construct() {
        $this->server_name = DB_HOST;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->database = DB_NAME;
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
