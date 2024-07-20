<?php

use helpers\Helper;
use Models\DynamicQuery;
   
require_once '../app/models/DynamicQuery.php';
require_once '../app/helpers/DataValidator.php'; 
require_once '../app/helpers/Helper.php'; 
require_once 'UserController.php';



class ContentController{
    private static $Query;

    public static function init() {
        self::$Query = new DynamicQuery("contenido");
    }



    


public static function update() {
    UserController::verificarSesion();
    self::init();
    header('Content-Type: application/json');

    // Obtener datos JSON de la solicitud POST
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    //var_dump($data);
    // Verificar errores en la decodificación del JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        $message = 'Error al decodificar el JSON';
        $responseData = ['success' => false, 'message' => $message, 'data' => null];
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    // Verificar si los datos recibidos están vacíos
    if (empty($data) || self::arrayValuesEmpty($data)) {
        $message = 'Datos vacíos';
        $responseData = ['success' => false, 'message' => $message, 'data' => null];
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    // Formatear los datos a JSON si es necesario
    $jsonFormat = Helper::FormatJson($data);

    // Procesar los datos recibidos (ejemplo: actualizar en la base de datos)
    $result = self::$Query->actualizar($jsonFormat);

    // Preparar la respuesta según el resultado del proceso
    if ($result['success']) {
        $message = 'Datos actualizados exitosamente';
        $responseData = ['success' => true, 'message' => $message, 'data' => isset($result['data']) ? $result['data'] : null];
    } else {
        $message = $result['message'];
        $responseData = ['success' => false, 'message' => $message, 'data' => null];
    }

    // Enviar respuesta JSON al frontend
    echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;    
}


public static function load(){
    UserController::verificarSesion();
    self::init();
    $name = 'id_usuario_contenido';
    header('Content-Type: application/json');
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    //$column = implode(", ", array_keys($data));

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
        exit;
    } 
    $query = Helper::BuilQuery($data, $name);
    $result = self::$Query-> executeQuerysAll($query);
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;

}

private static function arrayValuesEmpty($array) {
    foreach ($array as $value) {
        if (!empty($value)) {
            return false;
        }
    }
    return true;
}
}
?>