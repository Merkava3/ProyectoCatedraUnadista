<?php

class RequesteIA {
    private $send;

    public function __construct($send) {   
        $this->send = $send;
    }

    private function getHeaders() {
        return [
            'x-rapidapi-key: ' . API_KEY,
            'x-rapidapi-host: chatgpt-42.p.rapidapi.com',
            'Content-Type: application/json'
        ];
    }

    private function getRequestBody() {
        return json_encode([
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $this->send
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

    private function sendRequest($url, $headers, $body) {
        $ch = curl_init();
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body
        ];

        curl_setopt_array($ch, $options);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            echo $response;
        }
        
        curl_close($ch);
    }

    public function handleRequest() {
        $headers = $this->getHeaders();
        $body = $this->getRequestBody();
        $this->sendRequest(API_URL, $headers, $body);
    }
}
?>
