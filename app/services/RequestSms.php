<?php

class RequestSms {

  public static function ApiSms($data){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api103.hablame.co/api/sms/v3/send/priority",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'toNumber' => $data['number'],
            'sms' => $data['send'],
            'flash' => '0',
            'sc' => '890202',
            'request_dlvr_rcpt' => '0'
        ]),
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Account: " . SMS_ACCOUNT,
            "ApiKey: " . SMS_API_KEY,
            "Content-Type: application/json",
            "Token: " . SMS_TOKEN
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


?>