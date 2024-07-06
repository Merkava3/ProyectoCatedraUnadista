<?php

use Modelo\DynamicQuery;
require_once 'DataValidator.php';
require_once 'DynamicQuery.php';


class MainController {

    private static $Query;

    // Initialize the static Query instance
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
        self::init(); // Ensure initialization        
        header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON

        // Procesar la solicitud POST
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

       
        $validation = false;
        $message = '';
        $responseData = [];

        // Validar JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'Error al decodificar el JSON';
            $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
        } else {
            // Validar los datos
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
                    $message = $result['message']; // Mostrar mensaje de error específico
                }

                $responseData = ['success' => $validation, 'message' => $message, 'data' => Null];
            }
        }

        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // Asegúrate de que no haya más salida después de esto
    }

    public static function all(){
        self::verificarSesion();
        self::init(); // Ensure initialization
        $result = self::$Query->obtenerTodos();
        print_r($result);  
       }

    public static function getid(){
        self::verificarSesion();
        self::init(); 
        header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON
        
        // Procesar la solicitud GET
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        //var_dump($data);
        $column = implode(", ", array_keys($data));
        
        // Asegúrate de que la decodificación del JSON no tenga errores
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
            exit;
        }
    
        // Validar la identificación
        if (!isset($data[ $column ]) || !DataValidator::validateNumericString($data[ $column ])) {
            echo json_encode(['success' => false, 'message' => 'Identificacion no valida']);
            exit;
        }
    
        $result = self::$Query->obtenerPorId($data);
        
        // Devolver la respuesta JSON
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // Asegúrate de que no haya más salida después de esto
    }
    
    public static function delete(){
        self::verificarSesion();
        self::init(); 
        header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON
    
        // Procesar la solicitud DELETE
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    
        // Asegúrate de que la decodificación del JSON no tenga errores
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
            exit;
        }
    
        // Validar la identificación
        if (!isset($data['indentificacion']) || !DataValidator::validateNumericString($data['indentificacion'])) {
            echo json_encode(['success' => false, 'message' => 'Identificacion no valida']);
            exit;
        }
        
        $result = self::$Query->eliminar($data);
    
        // Devolver la respuesta JSON
        echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // Asegúrate de que no haya más salida después de esto
    }

    public static function update(){
        self::verificarSesion();
        self::init(); 
        header('Content-Type: application/json'); // Asegúrate de que la respuesta sea JSON        
        // Procesar la solicitud GET
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        var_dump($data);
        
        $validation = false;
        $message = '';
        $responseData = [];
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'Error al decodificar el JSON';
            $responseData = ['success' => $validation, 'message' => $message, 'data' => null];
        }else{
            $errors = DataValidator::validateData($data);
            if (!empty($errors)) {
                $message = 'Datos inválidos';
                $responseData = ['success' => $validation, 'message' => $message, 'errors' => $errors];
               
            }elseif(empty($data) || self::arrayValuesEmpty($data)){
                $message = 'Datos vacíos';
                $responseData = ['success' => $validation, 'message' => $message, 'data' => null];

            }else{                
                $result = self::$Query->actualizar($data);
                if ($result['success']) {
                    $validation = true;
                    $message = 'Datos Actualzidos';
                } else {                  
                    $message = $result['message']; // Mostrar mensaje de error específico
                }

                $responseData = ['success' => $validation, 'message' => $message, 'data' => Null];
            }
            echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit; // Asegúrate de que no haya más salida después de esto

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
        self::verificarSesion();
        self::init();
    
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id_usuario'];
    
            // Actualizar el estado de la sesión a false en la base de datos usando el método específico
            list($success, $stmtOrError) = self::$Query->actualizarEstadoSesion($userId);
    
            if (!$success) {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado de la sesión: ' . $stmtOrError], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                exit;
            }
        }
    
        // Destruir la sesión en PHP
        session_unset();
        session_destroy();
    
        // Devolver la respuesta JSON
        echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    

  public static function type(){
    self::verificarSesion();
    self::init();
    header('Content-Type: application/json');
    if (isset($_SESSION['user'])) {
        echo json_encode(['tipo_usuario' => $_SESSION['user']['tipo_usuario']]);
    } else {
        echo json_encode(['tipo_usuario' => null]);
    }
  }

  public static function users(){
    self::verificarSesion();
    self::init();
    header('Content-Type: application/json');
        
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    
    $querys = array(


    );
    list($success, $stmtOrError) = self::$Query->actualizarEstadoSesion();
    



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

