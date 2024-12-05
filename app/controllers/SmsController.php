<?php
require_once APP_PATH . '/../services/RequestSms.php';
require_once 'UserController.php';
require_once '../app/helpers/DataValidator.php';

class SmsController {

    public static function SendSms() {
        header('Content-Type: application/json');
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Debugging: Print the received data
        //echo "Received data: ";
      
        
        $validation = false;
        $message = '';
        $responseData = [];
        if (isset($data['send']) && isset($data['number'])) {
            $errors = DataValidator::ValidateSms($data);
            if (!empty($errors)) {
                $message = 'Datos inválidos';
                $responseData = [
                    'success' => $validation,
                    'message' => $message,
                    'errors' => $errors
                ];
                echo json_encode($responseData);
            } else {
                // Debugging: Check before making the API request
                //echo "No validation errors. Sending SMS...";
                echo RequestSms::ApiSms($data);         
            }
            
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Faltan los campos "send" o "number".'
            ]);
        }

        json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
}

?>
