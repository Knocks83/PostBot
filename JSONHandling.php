<?php
function getSauce($image_url) {
    require 'settings.php';
    $db = "5,9,12,21,22,25,26,34,36";

    $ch = curl_init("https://saucenao.com/search.php?db=$db&api_key=$api_key&output_type=2&numres=6&url=$image_url");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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

    if(property_exists($json->header, 'index')) {
        $found = array();
        $i = 0;
        foreach ($json->header->index as $index) {
            $i++;
            foreach ($websites as $website) {
                $id = $website->id;
                if($index->id == $website->id && $index->status == 0) {
                    $website->exists = true;
                    $website->url = $json->results[$i]->data->ext_urls[0];
                    array_push($found, $website);
                }
            }
        }
        unset($i);  // I delete the counter variable because it isn't needed anymore
        
        // Example processing of the obtained array
        foreach ($found as $key => $value) {
            echo("$value->name\t$value->url\n");
        }
    } else die('Error: nothing found');
}

