<?php
// Cargar el archivo autoload.php para la carga automática de clases
require_once '../app/autoload.php';

// Incluir el archivo de inicialización para cargar configuraciones y funciones comunes
require_once '../app/init.php';

// Función para cargar automáticamente los controladores
spl_autoload_register(function ($class_name) {
    // Ruta relativa a la carpeta controllers
    $class_path = '../app/controllers/' . $class_name . '.php';
    if (file_exists($class_path)) {
        require_once $class_path;
    }
});

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : 'user/index'; // Si no se especifica URL, se usa 'user/index' como default

// Dividir la URL en partes
$urlParts = explode('/', rtrim($url, '/'));

// Obtener el nombre del controlador y el método
$controllerName = ucfirst($urlParts[0]) . 'Controller';
$methodName = isset($urlParts[1]) ? $urlParts[1] : 'index'; // Método por defecto es 'index'

// Ruta del controlador
$controllerPath = '../app/controllers/' . $controllerName . '.php';

// Verificar si el archivo del controlador existe y cargarlo
if (file_exists($controllerPath)) {
    require_once $controllerPath;

    // Verificar si la clase del controlador existe
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        // Verificar si el método existe en el controlador y llamarlo
        if (method_exists($controller, $methodName)) {
            // Llamar al método con los parámetros restantes de la URL
            call_user_func_array([$controller, $methodName], array_slice($urlParts, 2));
        } else {
            // Método no encontrado
            require_once '../app/views/error404.html';
        }
    } else {
        // Clase del controlador no encontrada
        require_once '../app/views/error404.html';
    }
} else {
    // Archivo del controlador no encontrado
    require_once '../app/views/error404.html';
}

/* 
Funciones rtrim() y explode():
Funcion rtrim($url, '/') :
- rtrim() es una función de PHP que elimina los caracteres especificados del final de una cadena. En este caso, se está eliminando la barra inclinada / del final de la cadena $url.
- Por ejemplo, si $url es "index/index/", después de rtrim($url, '/'), la cadena será "index/index".
- Esto es útil para evitar barras adicionales al final de la URL que podrían interferir con el procesamiento de la misma.
-----------------------------------------------------------------------------------------------------------------------------------------------
Funcion explode('/', $url) :
- explode() es una función de PHP que divide una cadena en un array.delimitador especificado. En este caso, el delimitador es la barra inclinada /.
- Después de rtrim, si $url es "index/index", explode('/', $url) dividirá esta cadena en un array de dos elementos: ['index', 'index'].
- Esto permite separar la URL en sus componentes individuales, lo cual es necesario para determinar el controlador y el método.
----------------------------------------------------------------------------------------------------------------------------------------------------
Proceso completo:

- Entrada: Supongamos que la variable $url tiene el valor "index/index/".
- Aplicación de rtrim: La función rtrim($url, '/') elimina la barra inclinada al final, resultando en "index/index".
- Aplicación de explode: La función explode('/', 'index/index') divide esta cadena en ['index', 'index'].
--------------------------------------------------------------------------------------------------------------------------------------------------------

funcion isset():
La función isset() en PHP se utiliza para verificar si una variable está definida y su valor no es NULL.
Esta función devuelve un valor booleano: true si la variable está definida y no es NULL,
y false en caso contrario. La sintaxis de isset() es la siguiente: isset(var_name);

sintaxis : 
isset(var_name);
----------------------------------------------------------------------------------------------------------------------------------------------------------------------

La función method_exists():
 en PHP se utiliza para verificar si un método (función miembro) 
específico existe en una clase o un objeto. Esto es útil 
para asegurarse de que un método puede ser llamado sin causar un error.
sintaxis : 

bool method_exists ( mixed $object_or_class , string $method_name ) :

- $object_or_class: El nombre de la clase (como una cadena) o una instancia de la clase (objeto).
- $method_name: El nombre del método que desea verificar.
----------------------------------------------------------------------------------------------------------------------------------------------------------

La función call_user_func() en PHP es una función de alto nivel que permite llamar a una función o método de una manera dinámica. Esta función es útil
cuando el nombre de la función o el método que deseas llamar no se conoce hasta tiempo de ejecución.





----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Las funciones de alto nivel: en PHP son una característica fundamental del lenguaje de programación.
 Estas funciones permiten agrupar instrucciones que se ejecutan repetidamente,
  mejorando la organización del código y facilitando su mantenimiento y depuración.


  Ventajas de las funciones en PHP
Las funciones en PHP ofrecen varias ventajas:
Reutilización de código: Las funciones permiten reutilizar bloques de código en diferentes partes del programa,
reduciendo la duplicación de código y facilitando la modificación de la lógica de negocio.
Pasar argumentos: Las funciones pueden recibir argumentos que se pueden utilizar dentro de la función para realizar operaciones específicas.
Devolver valores: Las funciones pueden devolver valores que se pueden utilizar en otras partes del programa.
Funciones internas y anónimas: PHP ofrece funciones internas, como echo y print, y también permite definir funciones anónimas, que se pueden utilizar en situaciones específicas

*/
?>


