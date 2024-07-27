<?php

require_once APP_PATH . '/../services/RequestSms.php';
require_once 'UserController.php';
class SmsController {

    public static function SendSms() {
        //UserController::verificarSesion();
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (isset($data['send']) && isset($data['number'])) {
            RequestSms::ApiSms($data);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Faltan los campos "send" o "number".'
            ]);
        }
    }
}

?>
