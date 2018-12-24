<?php
require 'settings.php';
$baseUrl = "https://api.telegram.org/bot$botToken/setwebhook?";
$param = urlencode("url=$dirPath?fpam=$fpam%26token=$botToken");
$ch = curl_init();

$curlOptions = array(
    CURLOPT_URL => $baseUrl.$param,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true
);
curl_setopt_array($ch, $curlOptions);

$answer = json_decode(curl_exec($ch));

print($answer->error_code." - ".$answer->description);
