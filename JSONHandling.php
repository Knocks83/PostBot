<?php
    // You can find/create a API key here: https://saucenao.com/user.php?page=search-api
    $api_key = 'Insert your API sauceNAO key here';
    $image_url = 'Insert the image URL here';
    
    // Decode JSON data to PHP object
    $decodedJSON = json_decode($json);

    // Creates various objects with the websites supported by SauceNAO (not all)    
    $pixiv  = new \stdClass();      $pixiv->id = 5;         $pixiv->name = 'Pixiv';
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

    $json = file_get_contents("https://saucenao.com/search.php?db=5,9,12,21,22,25,26,34,36&api_key=$api_key&output_type=2&numres=6&url=$image_url");

    // Checks if the pic was found in the websites (by checking if the ID is in the header and doesn't have any error)
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
