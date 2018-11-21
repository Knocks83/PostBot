<?php
    //You can find/create a API key here: https://saucenao.com/user.php?page=search-api
    $api_key = 'Insert your API sauceNAO key here';
    $image_url = 'Insert the image URL here';
    
    // Decode JSON data to PHP object
    $decodedJSON = json_decode($json);
    
    $pixiv  = new \stdClass();      $pixiv->id = 5;         $pixiv->buttonName = 'Pixiv';
    $danbooru = new \stdClass();    $danbooru->id = 9;      $danbooru->buttonName = 'Danbooru';
    $yandere = new \stdClass();     $yandere->id = 12;      $yandere->buttonName = 'Yande.re';
    $animes = new \stdClass();      $animes->id = 21;       $animes->buttonName = 'Anime source';
    $hAnime = new \stdClass();      $hAnime->id = 22;       $hAnime->buttonName = 'H source';
    $gelbooru = new \stdClass();    $gelbooru->id = 25;     $gelbooru->buttonName = 'Gelbooru';
    $konaChan = new \stdClass();    $konaChan->id = 26;     $konaChan->buttonName = 'KonaChan';
    $deviantArt = new \stdClass();  $deviantArt->id = 34;   $deviantArt->buttonName = 'DevArt';
    $manga = new \stdClass();       $manga->id = 36;        $manga->buttonName = 'Manga';

    //Creates an array with every website I want to check
    $websites = [$pixiv,$danbooru,$yandere,$animes,$hAnime,$gelbooru,$konaChan,$deviantArt,$manga];

    $json = file_get_contents("https://saucenao.com/search.php?db=5,9,12,21,22,25,26,34,36&api_key=$api_key&output_type=2&numres=6&url=$image_url");

    //Checks if the pic was found in the websites (by checking if the ID is in the header and doesn't have any error)
    $found;

    foreach ($websites as $website) {
        $id = $website->id;
        if(property_exists($decodedJSON->header->index, $id)) {
            if ($decodedJSON->header->index->$id->status == 0) {
                $website->exists = true;
                array_push($found, $website);
            }
        }
    }
