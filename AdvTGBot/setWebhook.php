<?php
$fpam = '';
$token = '';
$dir = '';
$baseUrl = "https://api.telegram.org/bot$token/setwebhook?";
$ch = curl_init();

$param = urlencode("url=$dir?fpam=$fpam%26token=$token");

$curlOptions = array(
    CURLOPT_URL => $baseUrl.$param,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true
);
curl_setopt_array($ch, $curlOptions);

$answer = json_decode(curl_exec($ch));

print($answer->error_code." - ".$answer->description);
