<?php
require '../JSONHandling.php';
if (in_array($TGBot->chat_id, $fromWho) && isset($TGBot->photo)) {
    $filePath = json_decode($TGBot->getFile($TGBot->photo_file_id))->result->file_path;
    $photoUrl = "https://api.telegram.org/file/bot$botToken/$filePath";

    unset($filePath);
    do {
        $results = getSauce($photoUrl, $check_output);

        // If it exceeds the maximum request rate it waits then tries again
        $error = (isset($results['error']) && $results['error'] == '-2') ? true : false;
        if ($error) {
            sleep(30);
        }
    }while($error);

    $mostSimilar = ['similarity' => 0, 'url' => ''];

    foreach ($results as $result) {
        if($result->similarity > $mostSimilar['similarity'] && isset($result->url)) {
            $mostSimilar['similarity'] = $result->similarity;
            $mostSimilar['url'] = $result->url[0];
        }
    }

    unset($results);
    
    $button = new stdClass();
    $button->text = 'Sauce';
    $button->url = $mostSimilar['url'];

    $buttonMatrix = [
        [$button]
    ];

    $TGBot->sendPhoto($chatID,$TGBot->photo_file_id,null,$buttonMatrix);

    if ($logChat != '0000') {
        $TGBot->sendMessage($logChat, sprintf("User: [%s](tg://user?id=%s)\nFileID: `%s`",
            $TGBot->first_name.' '.$TGBot->last_name,
            $TGBot->user_id,
            $TGBot->photo_file_id
        ));
    }
}
