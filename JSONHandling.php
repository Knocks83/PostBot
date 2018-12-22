<?php
function getSauce($image_url, $checkIDs = false) {
    require 'settings.php';
    $db = [5,9,12,21,22,25,26,34,36];
    $url = "https://saucenao.com/search.php";
    
    // Reading the content of the file DBs.json (containing the list of the DBs)
    $filename = 'DBs.json';
    $data = fopen($filename, 'rb');
    $size = filesize($filename);
    $dbs = json_decode(fread($data, $size));
    fclose($data);
    unset($data, $size, $filename);

    $ch = curl_init();

    // Creates a array with the values to be sent and builds a string to send it via POST
    $post = [
        'api_key' => $api_key,
        'db' => $db,
        'output_type' => 2,
        'numres' => 6,
        'url' => $image_url
    ];
    $post_string = http_build_query($post);

    //Creates a array with the curl options, then sets it.
    $curlOptions = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => count($post),
        CURLOPT_POSTFIELDS => $post_string,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $curlOptions);

    $json = curl_exec($ch);

    // Decode JSON data to PHP object
    $json = json_decode($json);

    unset($ch, $post, $post_string, $curlOptions);

    $websites = new \stdClass();
    foreach ($db as $id) {
        if(isset($dbs->$id)) {
            $websites->$id = $dbs->$id;
        } else print("ID not found ($id)\n");
    }

    /*
    Checks if the pic was found in the websites (by checking if the ID is in the header and doesn't have any error)
    If it finds a website matching those characteristics it sets the property url to the website url
    */
    if(isset($json->results) && property_exists($json->header, 'index')) {
        $found = array();
        if ($checkIDs) {
            foreach ($json->results as $result) {
                $id = $result->header->index_id;
                if (isset($websites->$id) && property_exists($result->data, 'ext_urls')) {
                    if(isset($websites->$id->similarity)) {
                        if ($websites->$id->similarity < floatval($result->header->similarity)) {
                            $websites->$id->similarity = floatval($result->header->similarity);
                            $websites->$id->url = $result->data->ext_urls[0];
                            $websites->$id->id = $id;
                        }
                    } else {
                        $websites->$id->similarity = floatval($result->header->similarity);
                        $websites->$id->url = $result->data->ext_urls[0];
                        $websites->$id->id = $id;
                    }
                    array_push($found, $websites->$id);
                }
            }
        } else {
            foreach ($json->results as $result) {
                if(property_exists($result->data, 'ext_urls')) {
                    $tempArray = new stdClass();
                    $tempArray->similarity = floatval($result->header->similarity);
                    $tempArray->id = $result->header->index_id;
                    $tempArray->url = $result->data->ext_urls;
                    array_push($found, $tempArray);
                }
            }
        }
        
    } else die('Error: nothing found');

    return $found;
}

