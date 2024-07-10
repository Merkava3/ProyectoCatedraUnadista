<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'servidor');
define('DB_PASS', '');
define('DB_NAME', 'catedra');

// Otros parámetros de configuración
//define('BASE_URL', 'http://tu-dominio/public');

// Autocarga de clases usando spl_autoload_register
spl_autoload_register(function ($class_name) {
    $class_path = APP_PATH . '/../models/' . $class_name . '.php';
    if (file_exists($class_path)) {
        require_once $class_path;
        echo "Clase $class_name cargada desde $class_path<br>";
    }
});

// Otras configuraciones y funciones comunes

// Iniciar sesión si es necesario
//session_start();

// Funciones de ayuda comunes
//require_once APP_PATH . '/../helpers/Helper.php';

// Código adicional de inicialización según sea necesario


?>