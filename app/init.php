<?php
// Configuración de la aplicación
define('APP_PATH', realpath(dirname(__FILE__))); // Ruta absoluta de la carpeta app
define('BASE_URL', 'http://localhost/ProyectoCatedraUnadista/public/'); // URL base de tu proyecto

// Configuración de la base de datos
require_once 'config/config.php'; // Archivo con configuraciones de base de datos

// Autocarga de clases usando spl_autoload_register
spl_autoload_register(function ($class_name) {
    $class_path = APP_PATH . '/models/' . $class_name . '.php';
    if (file_exists($class_path)) {
        require_once $class_path;
    }
});

// Otras configuraciones y funciones comunes

// Iniciar sesión si es necesario
//session_start();

// Funciones de ayuda comunes
//require_once 'helpers/Helper.php';

// Código adicional de inicialización según sea necesario

?>
