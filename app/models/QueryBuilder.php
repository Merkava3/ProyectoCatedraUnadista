<?php
namespace models;

use helpers\Helper;

class QueryBuilder {

    public static function QueryInsert($tabla,$datos){
        $sql ="";
        $columns = implode(", ", array_keys($datos));
        $placeholders = implode(", ", array_fill(0, count($datos), '?'));        
        $sql = "INSERT INTO $tabla ($columns) VALUES ($placeholders)";
        return $sql;
    }

    public static function GetId($table,$datos){
        $column = implode(", ", array_keys($datos));
        $params = array_values($datos);
        $query = "SELECT * FROM {$table} WHERE {$column} = ?";
        return [$params, $query];
    }

    public static function QueryGetAll($table){
        $sql = "SELECT * FROM {$table}";
        return $sql;
    }

    public static function QueryDelete($table, $datos){
        $sql ="";
        $column = implode(", ", array_keys($datos));
        $sql = "DELETE FROM {$table} WHERE {$column} = ?";
        return $sql;
    }

    public static function QueryUpdate($table, $datos) {
        $sql = "";       
    
        // Encontrar la clave del identificador de forma dinámica
        $idKey = null;
        foreach ($datos as $key => $value) {
            if (strpos($key, 'id_') === 0) {
                $idKey = $key;
                break;
            }
        }
    
        if (!$idKey) {
            return ['success' => false, 'message' => 'No se encontró una clave de identificación válida'];
        }
    
        // Separar el identificador del resto de los datos
        $id = $datos[$idKey];
        unset($datos[$idKey]);
        // Encriptar la contraseña si existe
        if (isset($datos['pws'])) {
            $datos['pws'] = Helper::EncriptarPws($datos['pws']);        }
    
        // Construir la consulta SQL
        $columns = implode(" = ?, ", array_keys($datos)) . " = ?";
        $values = array_values($datos);
        $values[] = $id;
        $sql = "UPDATE {$table} SET $columns WHERE $idKey = ?";
    
        // Verificar que todos los valores sean cadenas o números
        foreach ($values as &$value) {
            if (is_array($value)) {
                return ['success' => false, 'message' => 'Los valores no deben ser arrays'];
            }
            $value = addslashes($value);
        }
    
        unset($value); // Desreferenciar para evitar problemas con la variable en el bucle
    
        // Construir la consulta completa
        $completeSql = $sql;
        foreach ($values as $value) {
            $completeSql = preg_replace('/\?/', "'" . $value . "'", $completeSql, 1);
        }    
    
        // Imprimir la consulta completa
        return [$sql, $values];
    }


    public static function GetLogin($table,$datos){
        $sql = "";
        // Extraer los campos y valores
        $columns = array_keys($datos);  
        $params = array_values($datos);            
        // Crear la consulta SQL
        $sql = "SELECT * FROM {$table} WHERE {$columns[0]} = ?";
        return [$sql,  [$params[0]]];

    }
    

   
}

?>