<?php
// Incluir el archivo de configuración de la base de datos y los modelos
// Estos archivos contienen las definiciones de clases necesarias para manejar la base de datos y las operaciones de la aplicación

require_once 'Conexion.php';  // Proporciona la conexión a la base de datos
require_once 'DataAccessInterface.php'; // Define la interfaz que deben implementar las clases que acceden a la base de datos
require_once 'DatabaseHandler.php'; // Clase abstracta que implementa métodos comunes para el acceso a la base de datos
require_once 'DynamicQuery.php';   // Implementa consultas dinámicas en la base de datos
require_once 'MainController.php';  // Asegúrate de que este archivo existe. Define el controlador principal de la aplicación
require_once 'Helper.php'; // Contiene funciones auxiliares, como la encriptación de contraseñas


// Obtener la URL solicitada
// Si se ha pasado una URL a través de la solicitud GET, se utiliza esa URL; de lo contrario, se usa 'index/index' por defecto
$url = isset($_GET['url']) ? $_GET['url'] : 'index/index';

// Dividir la URL en partes
// Separa la URL en componentes individuales basados en el carácter '/'

$urlParts = explode('/', rtrim($url, '/'));
//print_r ($urlParts); // Esta línea de código está encargada de dividir la URL en partes, para que se pueda determinar qué controlador y método se deben utilizar.

// Obtener el controlador y el método
// Asigna 'MainController' como el controlador por defecto
$controllerName = 'MainController'; 
// Si hay una segunda parte en la URL, la usamos como nombre del método; de lo contrario, el método por defecto es 'crear'
// Predefined array of request types
$Requests = array("crear","all", "update", "delete", "query", "getid","login","logout","type");
$NameFunction = "";

// Loop through the predefined request array
foreach ($Requests as $request) {
    if (isset($urlParts[0]) && $request == $urlParts[0]) {
        $NameFunction = $request;        
        break;
    }
}

$methodName = isset($urlParts[1]) ? $urlParts[1] : $NameFunction;

// Verificar si el método existe en el archivo del controlador
// Comprueba si el método especificado existe en la clase del controlador
if (method_exists($controllerName, $methodName)) {
    // Llamar al método
    // Llama al método del controlador de forma dinámica
   call_user_func([$controllerName, $methodName]);
} else {
    // Mostrar página de error 404  
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error 404: Método no encontrado']);
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
