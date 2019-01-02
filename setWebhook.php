<?php
require 'settings.php';
$url = "https://api.telegram.org/bot$botToken/setwebhook?url=$dirPath?fpam=$fpam%26token=$botToken";
$ch = curl_init();

$curlOptions = array(
    CURLOPT_URL => $url,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true
);
curl_setopt_array($ch, $curlOptions);

$answer = json_decode(curl_exec($ch));

print($url."\n");
print($answer->error_code." - ".$answer->description);
