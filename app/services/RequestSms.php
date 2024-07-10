<?php

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
    'toNumber' => '573001118888',
    'sms' => 'SMS de prueba Hablame',
    'flash' => '0',
    'sc' => '890202',
    'request_dlvr_rcpt' => '0'
  ]),
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Account: ",
    "ApiKey: ",
    "Content-Type: application/json",
    "Token: "
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
?>