<?php
function getSauce($image_url) {
    require 'settings.php';
    $db = "5,9,12,21,22,25,26,34,36";
    $url = "https://saucenao.com/search.php";

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

    // Creates various objects with the websites supported by SauceNAO (not all)
    $pixiv = new \stdClass();       $pixiv->id = 5;         $pixiv->name = 'Pixiv';
    $danbooru = new \stdClass();    $danbooru->id = 9;      $danbooru->name = 'Danbooru';
    $yandere = new \stdClass();     $yandere->id = 12;      $yandere->name = 'Yande.re';
    $animes = new \stdClass();      $animes->id = 21;       $animes->name = 'Anime';
    $hAnime = new \stdClass();      $hAnime->id = 22;       $hAnime->name = 'Hentai';
    $gelbooru = new \stdClass();    $gelbooru->id = 25;     $gelbooru->name = 'Gelbooru';
    $konaChan = new \stdClass();    $konaChan->id = 26;     $konaChan->name = 'KonaChan';
    $deviantArt = new \stdClass();  $deviantArt->id = 34;   $deviantArt->name = 'DeviantArt';
    $manga = new \stdClass();       $manga->id = 36;        $manga->name = 'Manga';

    // Creates an array with every website I want to check
    $websites = [$pixiv,$danbooru,$yandere,$animes,$hAnime,$gelbooru,$konaChan,$deviantArt,$manga];

    /*
    Checks if the pic was found in the websites (by checking if the ID is in the header and doesn't have any error)
    If it finds a website matching those characteristics it sets the property url to the website url
    */
    if(isset($json->results) && property_exists($json->header, 'index')) {
        $found = array();
        $i = 0;
        foreach ($json->results as $result) {
            foreach ($websites as $website) {
                if($result->header->index_id == $website->id) {
                    if(isset($website->similarity)) {
                        if ($website->similarity < floatval($result->header->similarity)) {
                            $website->similarity = floatval($result->header->similarity);
                            $website->url = $result->data->ext_urls[0];
                        }
                    } else {
                        $website->similarity = floatval($result->header->similarity);
                        $website->url = $result->data->ext_urls[0];
                    }
                    array_push($found, $website);
                    break;
                }
            }
        }
    } else die('Error: nothing found');

    return $found;
}

