<?php 

use helpers\Helper;
use Models\DynamicQuery;
   
require_once '../app/models/DynamicQuery.php';
require_once '../app/helpers/DataValidator.php'; 
require_once '../app/helpers/Helper.php'; 

class QuestionController{

    private static $Query;

    public static function init() {
        self::$Query = new DynamicQuery("examen");
    }

    public static function crear(){
        //self::verificarSesion();
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        print_r($data);
        $validation = false;
        $message = '';
        $responseData = [];

        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'Error al decodificar el JSON';
            $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
        } else {            
           if (empty($data) || self::arrayValuesEmpty($data)) {
                $message = 'Datos vacíos';
                $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
            } else {                
                $result = self::$Query->created($data);
                if ($result['success']) {
                    $validation = true;
                    $message = 'Datos recibidos y guardados';
                } else {                  
                    $message = $result['message'];
                }
                $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
            }
        }

        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
        
    }

    public static function all() {
        //self::verificarSesion();
        self::init();
        $responseData= self::$Query->get();        
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function delete(){       
        //self::verificarSesion();
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
            exit;
        }

        if (!isset($data['id_examen']) || !DataValidator::validateNumericString($data['id_examen'])) {
            echo json_encode(['success' => false, 'message' => 'id_examen no valida']);
            exit;
        }
        
        $result = self::$Query->eliminar($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
    }

    public function getid(){
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        $column = implode(", ", array_keys($data));

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
            exit;
        }

        if (!isset($data[$column]) || !DataValidator::validateNumericString($data[$column])) {
            echo json_encode(['success' => false, 'message' => 'id no valida']);
            exit;
        }

        $result = self::$Query->obtenerPorId($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
        
    }

    public function update(){
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        //var_dump($data);
        $validation = false;
        $message = '';
        $responseData = [];
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'Error al decodificar el JSON';
            $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
        } else {
            if (empty($data) || self::arrayValuesEmpty($data)) {
                $message = 'Datos vacíos';
                $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
            } else {                
                $result = self::$Query->actualizar($data);
                if ($result['success']) {
                    $validation = true;
                    $message = 'Datos Actualzidos';
                } else {                  
                    $message = $result['message'];
                }
                $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
            }
            echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }

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