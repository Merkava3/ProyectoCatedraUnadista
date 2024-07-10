<?php

use Models\DynamicQuery;
require_once '../app/models/DynamicQuery.php';
require_once '../app/helpers/DataValidator.php'; 

class UserController {

    private static $Query;

    public static function init() {
        self::$Query = new DynamicQuery("usuario");
    }

    public static function verificarSesion() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.html');
            exit;
        }
    }

    public static function crear() {
        self::verificarSesion();
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
       
        $validation = false;
        $message = '';
        $responseData = [];

        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'Error al decodificar el JSON';
            $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
        } else {
            $errors = DataValidator::validateData($data);
            if (!empty($errors)) {
                $message = 'Datos inválidos';
                $responseData = ['success' => $validation, 'message' => $message, 'errors' => $errors];
            } elseif (empty($data) || self::arrayValuesEmpty($data)) {
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
        self::verificarSesion();
        self::init();
        $result = self::$Query->obtenerTodos();
        print_r($result);  
    }

    public static function getid() {
        self::verificarSesion();
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
            echo json_encode(['success' => false, 'message' => 'Identificacion no valida']);
            exit;
        }

        $result = self::$Query->obtenerPorId($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function delete() {
        self::verificarSesion();
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
            exit;
        }

        if (!isset($data['indentificacion']) || !DataValidator::validateNumericString($data['indentificacion'])) {
            echo json_encode(['success' => false, 'message' => 'Identificacion no valida']);
            exit;
        }
        
        $result = self::$Query->eliminar($data);
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function update() {
        self::verificarSesion();
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
            $errors = DataValidator::validateData($data);
            if (!empty($errors)) {
                $message = 'Datos inválidos';
                $responseData = ['success' => $validation, 'message' => $message, 'errors' => $errors];
            } elseif (empty($data) || self::arrayValuesEmpty($data)) {
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

    public static function login() {
        self::init();
        header('Content-Type: application/json');
        
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);     
    
        if (!isset($data['correo']) || !isset($data['pws'])) {
            echo json_encode(['success' => false, 'message' => 'Correo y contraseña son requeridos'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;
        }
    
        $result = self::$Query->login($data);
    
        if ($result['success']) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $result['data'];
            $_SESSION['loggedin'] = true; // Marcar la sesión como iniciada
    
            // Otros datos relevantes de sesión
            // $_SESSION['user_id'] = $result['data']['id'];
    
            echo json_encode(['success' => true, 'pages' => 'dashboard_tutor.html'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        exit;
    }

    public static function logout() {
        session_start();
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Sesión cerrada']);
        exit;
    }

    public static function type() {
        self::verificarSesion();
        self::init();
        $result = self::$Query->obtenerUsuariosPorTipo();
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function users() {
        self::verificarSesion();
        session_start();
        if (isset($_SESSION['user'])) {
            echo json_encode(['success' => true, 'user' => $_SESSION['user']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
        }
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
