<?php

require_once APP_PATH . '/../services/RequesteIA.php'; // Ajusta la ruta según sea necesario
require_once 'UserController.php';
class IAController {
    public static function ChatGpt4() {
        //UserController::verificarSesion();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (isset($data["send"])) {
            $request = new RequesteIA($data["send"]);
            $request->handleRequest();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se ha proporcionado el campo "send".'
            ]);
        }
    }
}
?>
