<?php
spl_autoload_register(function ($className) {
    // Definir el directorio base de tu aplicación
    $baseDir = __DIR__ . '/';

    // Convertir el nombre de la clase con namespace en una ruta de archivo
    $file = $baseDir . str_replace('\\', '/', $className) . '.php';

    // Verificar si el archivo existe y cargarlo
    if (file_exists($file)) {
        require $file;
    } else {
        // Manejar errores de carga de clase aquí si es necesario
        throw new Exception("Clase $className no encontrada");
    }
});
?>