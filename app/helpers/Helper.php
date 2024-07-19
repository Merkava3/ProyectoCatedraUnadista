<?php
namespace helpers;
require_once __DIR__ . '/../models/QueryInnerjoin.php';

class Helper{
    // Método para verificar Encriptar contraseña
    public static function EncriptarPws($pws){
        return  hash('sha256', $pws);
    }
    public static function verificarContraseña($password, $hashedPassword) {
        $hashedInputPassword = self::EncriptarPws($password);
        return hash_equals($hashedInputPassword, $hashedPassword);
    }

    //Método para eliminar contraseña del array
    public static function excludePassword($data) {
        foreach ($data as &$row) {
            unset($row['pws']);
        }
        return $data;
    }

    //Metodo de Formateto json para Conetnido
    public static function FormatJson($datos){
        $jsonColumns = ['contenido_sobre', 'nuestro_servicio', 'portafolio'];
        foreach ($jsonColumns as $column) {
            if (isset($datos[$column])) {
                $datos[$column] = json_encode($datos[$column]);
            }
        }
        return $datos;
    }

public static function BuilQuery($data, $name){
    global $Querycontenido;
    // Construir la consulta utilizando $data si es necesario
    // Por ejemplo, podrías concatenar $data a la consulta si necesitas filtrar por algún campo
    $query = $Querycontenido . " = " . $data[$name];
    return $query;
}



public static function Calculate($Question, $Answer) {   

    // Verificar y decodificar el JSON si es necesario
    if (is_string($Question)) {
        $Question = json_decode($Question, true);
    }
    if (is_string($Answer)) {
        $Answer = json_decode($Answer, true);
    }
    // Verificar que la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error al decodificar JSON: " . json_last_error_msg() . "\n";
        return 0;
    }

    // Acceder a las preguntas desde el contenido del examen
    if (!isset($Question['data'][0]['contenido'])) {
        echo "Error: 'contenido' no está definido en los datos de preguntas.\n";
        return 0;
    }

    $contenido = json_decode($Question['data'][0]['contenido'], true);

    // Verificar que la decodificación del contenido fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error al decodificar JSON del contenido: " . json_last_error_msg() . "\n";
        return 0;
    }

    // Acceder a las preguntas
    if (!isset($contenido['preguntas'])) {
        echo "Error: 'preguntas' no está definido en el contenido.\n";
        return 0;
    }

    $preguntas = $contenido['preguntas'];

    // Acceder a las respuestas del estudiante
    if ( (!isset($Answer['data']['respuestas']))) {
        echo "Error: 'respuestas' no está definido en los datos de respuestas del estudiante.\n";
        return 0;
    }

    $respuestasEstudiante = $Answer['data']['respuestas'];

    // Continuar con la lógica de cálculo de notas
    $totalPuntaje = 0;
    $puntajeObtenido = 0;

    foreach ($preguntas as $pregunta) {
        $idPregunta = $pregunta['id_pregunta'];
        $puntajePregunta = isset($pregunta['puntajea']) ? $pregunta['puntajea'] : 0;
        $totalPuntaje += $puntajePregunta;

        foreach ($respuestasEstudiante as $respuestaEstudiante) {
            if ($respuestaEstudiante['id_pregunta'] == $idPregunta) {
                $idRespuestaEstudiante = $respuestaEstudiante['id_respuesta'];

                foreach ($pregunta['respuestas'] as $respuesta) {
                    if ($respuesta['id_respuesta'] == $idRespuestaEstudiante && isset($respuesta['es_correcta']) && $respuesta['es_correcta']) {
                        $puntajeObtenido += $puntajePregunta;
                        break;
                    }
                }
                break;
            }
        }
    }

    $notaFinal = ($totalPuntaje > 0) ? ($puntajeObtenido / $totalPuntaje) * 100 : 0;
    return $notaFinal;
}
}












?>