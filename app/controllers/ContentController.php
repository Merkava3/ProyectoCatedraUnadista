<?php
 use Models\DynamicQuery;
   
require_once '../app/models/DynamicQuery.php';
require_once '../app/helpers/DataValidator.php'; 

class ContentController{
    private static $Query;

    public static function init() {
        self::$Query = new DynamicQuery("contenido");
    }



    public static function verificarSesion() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.html');
            exit;
        }
    }
    public static  function crear(){
        //self::verificarSesion();
        self::init();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        var_dump($data);
        $validation = false;
        $message = '';
        $responseData = [];
        
    }

  




}


?>