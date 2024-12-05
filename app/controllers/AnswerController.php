<?php

use helpers\Helper;
use Models\DynamicQuery;

   
require_once '../app/models/DynamicQuery.php';
require_once '../app/helpers/DataValidator.php'; 
require_once '../app/helpers/Helper.php'; 
require_once '../app/Models/QueryInnerjoin.php'; 
require_once 'UserController.php';


class AnswerController{

    private static $Query;

    public static function init() {
        self::$Query = new DynamicQuery("respuesta_estudiante");
    }

    public static function crear() {
        //UserController::verificarSesion();
        self::init();
        global $QueryExamen;

        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Verificar que la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al decodificar JSON de entrada: ' . json_last_error_msg(),
                'data' => null
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return;
        }

        // Decodificar el campo "data" si es una cadena JSON
        if (isset($data['data']) && is_string($data['data'])) {
            $data['data'] = json_decode($data['data'], true);

            // Verificar que la decodificación del campo "data" fue exitosa
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al decodificar JSON del campo "data": ' . json_last_error_msg(),
                    'data' => null
                ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                return;
            }
        }

        // Ejecutar la consulta para obtener los datos del examen
        $result = self::$Query->executeQuerysAll($QueryExamen);

        // Verificar si la consulta devolvió datos válidos
        if (!$result || empty($result['data'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: No se encontraron datos del examen.',
                'data' => null
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return;
        }

        // Pasar los datos a la función Calculate
        $nota = Helper::Calculate($result, $data);      

        // Crear el diccionario para la inserción
        $AnswerInsert = [
            "id_estudiante" => $data["id_estudiante"],
            "id_examen" => $data["id_examen"],
            "respuestas" => json_encode($data["data"]["respuestas"]),
            "Nota" => $nota
        ];

        // Insertar los datos en la base de datos
        $result = self::$Query->created($AnswerInsert);

        // Preparar la respuesta
        if ($result['success']) {
            $responseData = [
                'success' => true,
                'message' => 'Datos recibidos y guardados',
                'data' => null
            ];
        } else {
            $responseData = [
                'success' => false,
                'message' => $result['message'],
                'data' => null
            ];
        }

        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
        
        
   
}
}

?>