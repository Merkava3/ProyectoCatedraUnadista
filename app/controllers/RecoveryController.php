<?php

require_once __DIR__ .'/../config/config.php';

class RecoveryController
{
    private $apiUrl;
    private $apiKey;
    private $apiHost;

    public function __construct()
    {
        $this->apiUrl = RAPIDMAIL_API_URL;
        $this->apiKey = RAPIDMAIL_API_KEY;
        $this->apiHost = RAPIDMAIL_API_HOST;
    }

    public function email()
    {
        $curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://rapidmail.p.rapidapi.com/",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => json_encode([
		'ishtml' => 'false',
		'sendto' => 'lennzoferrari@hotmail.com',
		'name' => 'Put Any Custom Name here',
		'replyTo' => 'your mail where users can send reply',
		'title' => 'Testing RapidMail Api',
		'body' => 'Put your Text body here, if you want to send html just set the ishtml: true in the request body'
	]),
	CURLOPT_HTTPHEADER => [
		"Content-Type: application/json",
		"x-rapidapi-host: rapidmail.p.rapidapi.com",
		"x-rapidapi-key: 256eadd6c0msh6925a86ac675d10p19734bjsna5c667936a72"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
       
    }

    
}



// Uso de la clase



?>
