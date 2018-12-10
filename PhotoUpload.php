<?php
function uploadPhoto($image) {
    require 'settings.php';
    $url = 'https://api.imgur.com/3/image';

    // Encode it to base64 to send it via POST
    $data = fopen($image, 'rb');
    $size = filesize($image);
    $contents = fread($data, $size);
    fclose($data);
    $encodedContents = base64_encode($contents);
    unset($data, $size, $contents);

    // Create a array containing the POST headers
    $header = array(
        "Authorization: Client-ID $client_id"
    );

    // Create a array with the POST parameters
    $post = [
        'image' => $encodedContents
    ];

    // Convert the POST parameters to a string so them can be sent via POST
    $post_string = http_build_query($post);

    // cUrl setup
    $ch = curl_init();
    $curlOptions = array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_POST => count($post),
        CURLOPT_POSTFIELDS => $post_string,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $curlOptions);

    $result = json_decode(curl_exec($ch));

    return $result;
}

