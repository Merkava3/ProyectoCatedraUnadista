<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'servidor');
define('DB_PASS', '');
define('DB_NAME', 'catedra');
// Claves de la API de ChatGPT
const API_URL = 'https://chatgpt-42.p.rapidapi.com/conversationgpt4-2';
const API_KEY = '2649fb8baemsh42963d06b79500bp12a0e7jsn5a9fb3255f0f';

// Claves de la API de SMS
define('SMS_ACCOUNT', '10019863');
define('SMS_API_KEY', 'AXqKAPuD7gYRvIzyiMB5ld4K36z4qS');
define('SMS_TOKEN', '082a0dbd6a147c5d5a86c44fd2ea8959');


// Claves de la API de RapidMailSender
define('RAPIDMAIL_API_URL', 'https://rapidmail.p.rapidapi.com/');
define('RAPIDMAIL_API_KEY', '256eadd6c0msh6925a86ac675d10p19734bjsna5c667936a72');
define('RAPIDMAIL_API_HOST', 'rapidmail.p.rapidapi.com');


// Ruta de la aplicación
define('APP_PATH', __DIR__);

// Autocarga de clases usando spl_autoload_register
spl_autoload_register(function ($class_name) {
    // Asumiendo que las clases están en la carpeta 'models' o 'controllers'
    $class_path = APP_PATH . '/../models/' . $class_name . '.php';
    if (file_exists($class_path)) {
        require_once $class_path;
        echo "Clase $class_name cargada desde $class_path<br>";
    } else {
        $class_path = APP_PATH . '/../controllers/' . $class_name . '.php';
        if (file_exists($class_path)) {
            require_once $class_path;
            echo "Clase $class_name cargada desde $class_path<br>";
        }
    }
});
?>
