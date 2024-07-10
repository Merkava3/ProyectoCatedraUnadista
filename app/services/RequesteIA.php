<?php

class RequesteIA {
    // Definimos las constantes
    const API_URL = 'https://chatgpt-42.p.rapidapi.com/conversationgpt4-2';
    const API_KEY = '2649fb8baemsh42963d06b79500bp12a0e7jsn5a9fb3255f0f';

    // Función para obtener las cabeceras
    private function getHeaders() {
        return [
            'x-rapidapi-key: ' . self::API_KEY,
            'x-rapidapi-host: chatgpt-42.p.rapidapi.com',
            'Content-Type: application/json'
        ];
    }

    // Función para obtener el cuerpo de la solicitud
    private function getRequestBody() {
        return json_encode([
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'me puedes ayudar con un contenido de catedra unadista pero orientado al programa de psicologia'
                ]
            ],
            'system_prompt' => '',
            'temperature' => 0.9,
            'top_k' => 5,
            'top_p' => 0.9,
            'max_tokens' => 256,
            'web_access' => null
        ]);
    }

    // Función para enviar la solicitud
    private function sendRequest($url, $headers, $body) {
        $ch = curl_init();
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body
        ];

        foreach ($options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            echo $response;
        }
        
        curl_close($ch);
    }

    // Método para manejar la solicitud
    public function handleRequest() {
        $headers = $this->getHeaders();
        $body = $this->getRequestBody();
        $this->sendRequest(self::API_URL, $headers, $body);
    }
}
?>
