<?php
namespace helpers;
require_once __DIR__ . '/../models/QueryInnerjoin.php';

class Helper{
    // Método para verificar Encriptar contraseña
    public static function EncriptarPws($pws){
        return  hash('sha256', $pws);
    }
    public static function verificarContraseña($password, $hashedPassword) {
        $hashedInputPassword = self::EncriptarPws($password);
        return hash_equals($hashedInputPassword, $hashedPassword);
    }

    //Método para eliminar contraseña del array
    public static function excludePassword($data) {
        foreach ($data as &$row) {
            unset($row['pws']);
        }
        return $data;
    }

    //Metodo de Formateto json para Conetnido
    public static function FormatJson($datos){
        $jsonColumns = ['contenido_sobre', 'nuestro_servicio', 'portafolio'];
        foreach ($jsonColumns as $column) {
            if (isset($datos[$column])) {
                $datos[$column] = json_encode($datos[$column]);
            }
        }
        return $datos;
    }

public static function BuilQuery($data, $name){
    global $Querycontenido;
    // Construir la consulta utilizando $data si es necesario
    // Por ejemplo, podrías concatenar $data a la consulta si necesitas filtrar por algún campo
    $query = $Querycontenido . " = " . $data[$name];
    return $query;
}
}

?>