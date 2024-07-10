<?php
namespace Models;

use models\Conexion;
use models\DataAccessInterface;

abstract class DatabaseHandler implements DataAccessInterface {
    protected $conexion;

    public function __construct() {
        $this->conexion = Conexion::getInstancia()->getConexion();
    }

    abstract public function obtenerTodos();
    abstract public function obtenerPorId($id);
    abstract public function created($datos);
    abstract public function actualizar($datos);
    abstract public function eliminar($datos);

    protected function prepareAndExecute($query, $types = '', $params = []) {
        // Preparar la consulta
          
        $stmt = mysqli_prepare($this->conexion, $query);
        //var_dump($stmt);  
        if ($stmt === false) {           
            return [false, "Error en mysqli_prepare: " . mysqli_error($this->conexion)];
        }
    
        // Si hay tipos y parámetros, vincularlos
        if (!empty($types) && !empty($params)) {                           
            if (!is_array($params[0])) {
                $params = [$params]; // Convertir a array de arrays si no lo es
            }
    
            if (!mysqli_stmt_bind_param($stmt, $types, ...$params[0])) {
         
                return [false, "Error en mysqli_stmt_bind_param: " . mysqli_stmt_error($stmt)];
            }
    
            // Ejecutar la consulta
            if (!mysqli_stmt_execute($stmt)) {
              
                return [false, "Error en mysqli_stmt_execute: " . mysqli_stmt_error($stmt)];
            }
        } else {
            // Ejecutar la consulta sin parámetros
            if (!mysqli_stmt_execute($stmt)) {
               
                return [false, "Error en mysqli_stmt_execute: " . mysqli_stmt_error($stmt)];
            }
        }
    
        return [true, $stmt];
    }

    public function cerrarConexion() {
        Conexion::getInstancia()->cerrarConexion();
    }
}
?>
