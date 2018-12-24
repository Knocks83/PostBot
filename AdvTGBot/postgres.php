<?php
/*This Source Code Form is subject to the terms of the Mozilla Public
  License, v. 2.0. If a copy of the MPL was not distributed with this
  file, You can obtain one at http://mozilla.org/MPL/2.0/.*/
if ($TGBot->settings['adminPostGreSQL']) {
    try {
        //Grazie ad alsogamer per avermi aiutato con la creazione della tabella che non funzionava
        $TGBot->pdb->query("CREATE TABLE IF NOT EXISTS $TGBot->table_name(
            chat_id BIGINT NOT NULL,
            first_name TEXT , 
            last_name TEXT ,
            username TEXT , 
            action TEXT NOT NULL ,
            title TEXT ,
            type TEXT ,
            to_update TEXT
            );");
    } catch (PDOException $e) {
    }
    $la = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE chat_id = ?");
    $la->execute([$TGBot->chat_id]);
    $la = $la->fetch(\PDO::FETCH_ASSOC);
    if ($TGBot->chat_id != $la['chat_id']) {
        $insertprep = $TGBot->pdb->prepare("INSERT INTO $TGBot->table_name (chat_id, first_name, last_name, username, action, title, type, to_update) VALUES (?,?,?,?,?,?,?,?)");
        if ($TGBot->type == 'supergroup' or $TGBot->type == 'group' or $TGBot->type == 'channel') {
            $insertprep->execute([$TGBot->chat_id, null, null, null, 'none', $TGBot->title, $TGBot->type, true]);
        } else {
            $insertprep->execute([$TGBot->chat_id, $TGBot->first_name, $TGBot->last_name, $TGBot->username, 'none', null, $TGBot->type, true]);
        }
    } else {
        if ($la['to_update']) {
            if (in_array($TGBot->type, ['supergroup', 'group', 'channel'])) {
                $update = $TGBot->pdb->prepare("UPDATE $TGBot->table_name SET title=?, type=?, to_update=? WHERE chat_id=?");
                $update->execute([$TGBot->title, $TGBot->type, true, $TGBot->chat_id]);
            } else {
                $update = $TGBot->pdb->prepare("UPDATE $TGBot->table_name SET first_name=?, last_name=?, username=?, type=?, to_update=? WHERE chat_id=?");
                $update->execute([$TGBot->first_name, $TGBot->last_name, $TGBot->username, $TGBot->type, true, $TGBot->chat_id]);
            }
        }
    }

    if ($TGBot->text == '/admin' and $TGBot->botAdmin() or $TGBot->cbdata_text == '/admin' and $TGBot->botAdmin()) {
        $buttons[] = [
        [
            'text'          => 'Users Number',
            'callback_data' => '/unumber',
        ],
    ];
        $buttons[] = [
        [
            'text'          => 'Global Post',
            'callback_data' => '/globalpost',
        ],
    ];
        $text = 'Ok! Perfect, now, select what to do.';
        if ($TGBot->cbdata) {
            $TGBot->editMessage($TGBot->chat_id, $TGBot->message_id, $text, $buttons);
        } else {
            $TGBot->sendMessage($TGBot->chat_id, $text, $buttons);
        }
    }

    if ($TGBot->cbdata_text == '/unumber' and $TGBot->botAdmin()) {
        $buttons[] = [
        [
            'text'          => 'Admin Panel',
            'callback_data' => '/admin',
        ],
    ];
        $private = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
        $private->execute(['private']);
        $groups = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
        $groups->execute(['group']);
        $supergroup = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
        $supergroup->execute(['supergroup']);
        $channel = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
        $channel->execute(['channel']);
        $TGBot->editMessage($TGBot->chat_id, $TGBot->message_id, "<b>Here is the list of all users</b> \n👤 Private chat: ".$private->rowCount()."\n👥 Groups: ".$groups->rowCount()."\n👥 Supergroups: ".$supergroup->rowCount()."\n🗣 Channels: ".$channel->rowCount(), $buttons);
    }

    if ($TGBot->cbdata_text == '/globalpost' and $TGBot->botAdmin()) {
        $buttons[] = [
        [
            'text'          => 'Only Groups',
            'callback_data' => '/gpost g',
        ],
        [
            'text'          => 'Only Users',
            'callback_data' => '/gpost u',
        ],
    ];
        $buttons[] = [
        [
            'text'          => 'Both',
            'callback_data' => '/gpost b',
        ],
    ];
        $buttons[] = [
        [
            'text'          => 'Cancel operation',
            'callback_data' => '/gpost cancel',
        ],
    ];
        $TGBot->editMessage($TGBot->chat_id, $TGBot->message_id, 'Ok! Perfect, now, select who must receive the message.', $buttons);
    }
    if (strpos($TGBot->cbdata_text, '/gpost') === 0 and $TGBot->botAdmin()) {
        $t = explode(' ', $TGBot->cbdata_text);
        if ($t[1] == 'cancel') {
            $TGBot->deleteMessage($TGBot->chat_id, $TGBot->message_id);
            $update = $TGBot->pdb->prepare("UPDATE $TGBot->table_name SET action=?, to_update=? WHERE chat_id=?");
            $update->execute(['none', true, $TGBot->chat_id]);
        } elseif ($t[1] == 'media' or $t[1] == 'sticker' or $t[1] == 'text') {
            $update = $TGBot->pdb->prepare("UPDATE $TGBot->table_name SET action=?, to_update=? WHERE chat_id=?");
            $update->execute(['post.'.$t[1].'_'.$t[2], false, $TGBot->chat_id]);
            $buttons[] = [
            [
                'text'          => 'Cancel operation',
                'callback_data' => '/gpost cancel',
            ],
        ];
            $TGBot->editMessage($TGBot->chat_id, $TGBot->message_id, 'Ok, now send me the '.$t[1].' and reply to the message with /send.', $buttons);
        } else {
            $buttons[] = [
            [
                'text'          => '💾 Media',
                'callback_data' => '/gpost media '.$t[1],
            ],
        ];
            $buttons[] = [
            [
                'text'          => 'Text Message',
                'callback_data' => '/gpost text '.$t[1],
            ],
        ];
            $buttons[] = [
            [
                'text'          => 'Cancel operation',
                'callback_data' => '/gpost cancel',
            ],
        ];
            $TGBot->editMessage($TGBot->chat_id, $TGBot->message_id, 'Ok, now select wich type of message do you want send.', $buttons);
        }
    }
    if ($TGBot->text == '/send' and isset($TGBot->reply_to_message) and $TGBot->botAdmin()) {
        $select = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE chat_id=?");
        $select->execute([$TGBot->chat_id]);
        $select = $select->fetch(PDO::FETCH_ASSOC);
        $ex = explode('_', str_replace('post.', '', $select['action']));
        if ($ex[1] == 'g') {
            $TGBot->sendMessage($TGBot->chat_id, "I'm sending the media in all groups.");
            $fetchAll = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
            $fetchAll->execute(['supergroup']);
            $fetchAll = $fetchAll->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchAll as $fetch) {
                if ($TGBot->reply_photo) {
                    $TGBot->sendPhoto($fetch['chat_id'], $TGBot->reply_photo_file_id, $TGBot->reply_photo_caption);
                }
                if ($TGBot->reply_to_message_text) {
                    $TGBot->sendMessage($fetch['chat_id'], $TGBot->reply_to_message_text);
                }
                if ($TGBot->reply_video) {
                    $TGBot->sendVideo($fetch['chat_id'], $TGBot->reply_video_file_id, $TGBot->reply_video_caption);
                }
                if ($TGBot->reply_document) {
                    $TGBot->sendDocument($fetch['chat_id'], $TGBot->reply_document_file_id, $TGBot->reply_document_caption);
                }
            }
            $fetchAll = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
            $fetchAll->execute(['group']);
            $fetchAll = $fetchAll->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchAll as $fetch) {
                if ($TGBot->reply_photo) {
                    $TGBot->sendPhoto($fetch['chat_id'], $TGBot->reply_photo_file_id, $TGBot->reply_photo_caption);
                }
                if ($TGBot->reply_to_message_text) {
                    $TGBot->sendMessage($fetch['chat_id'], $TGBot->reply_to_message_text);
                }
                if ($TGBot->reply_video) {
                    $TGBot->sendVideo($fetch['chat_id'], $TGBot->reply_video_file_id, $TGBot->reply_video_caption);
                }
                if ($TGBot->reply_document) {
                    $TGBot->sendDocument($fetch['chat_id'], $TGBot->reply_document_file_id, $TGBot->reply_document_caption);
                }
            }
        }
        if ($ex[1] == 'u') {
            $TGBot->sendMessage($TGBot->chat_id, "I'm sending the media to all users.");
            $fetchAll = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
            $fetchAll->execute(['private']);
            $fetchAll = $fetchAll->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchAll as $fetch) {
                if ($TGBot->reply_photo) {
                    $TGBot->sendPhoto($fetch['chat_id'], $TGBot->reply_photo_file_id, $TGBot->reply_photo_caption);
                }
                if ($TGBot->reply_to_message_text) {
                    $TGBot->sendMessage($fetch['chat_id'], $TGBot->reply_to_message_text);
                }
                if ($TGBot->reply_video) {
                    $TGBot->sendVideo($fetch['chat_id'], $TGBot->reply_video_file_id, $TGBot->reply_video_caption);
                }
                if ($TGBot->reply_document) {
                    $TGBot->sendDocument($fetch['chat_id'], $TGBot->reply_document_file_id, $TGBot->reply_document_caption);
                }
            }
        }
        if ($ex[1] == 'b') {
            $TGBot->sendMessage($TGBot->chat_id, "I'm sending the media in all groups and to all users.");
            $fetchAll = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
            $fetchAll->execute(['supergroup']);
            $fetchAll = $fetchAll->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchAll as $fetch) {
                if ($TGBot->reply_photo) {
                    $TGBot->sendPhoto($fetch['chat_id'], $TGBot->reply_photo_file_id, $TGBot->reply_photo_caption);
                }
                if ($TGBot->reply_to_message_text) {
                    $TGBot->sendMessage($fetch['chat_id'], $TGBot->reply_to_message_text);
                }
                if ($TGBot->reply_video) {
                    $TGBot->sendVideo($fetch['chat_id'], $TGBot->reply_video_file_id, $TGBot->reply_video_caption);
                }
                if ($TGBot->reply_document) {
                    $TGBot->sendDocument($fetch['chat_id'], $TGBot->reply_document_file_id, $TGBot->reply_document_caption);
                }
            }
            $fetchAll = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
            $fetchAll->execute(['group']);
            $fetchAll = $fetchAll->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchAll as $fetch) {
                if ($TGBot->reply_photo) {
                    $TGBot->sendPhoto($fetch['chat_id'], $TGBot->reply_photo_file_id, $TGBot->reply_photo_caption);
                }
                if ($TGBot->reply_to_message_text) {
                    $TGBot->sendMessage($fetch['chat_id'], $TGBot->reply_to_message_text);
                }
                if ($TGBot->reply_video) {
                    $TGBot->sendVideo($fetch['chat_id'], $TGBot->reply_video_file_id, $TGBot->reply_video_caption);
                }
                if ($TGBot->reply_document) {
                    $TGBot->sendDocument($fetch['chat_id'], $TGBot->reply_document_file_id, $TGBot->reply_document_caption);
                }
            }
            $fetchAll = $TGBot->pdb->prepare("SELECT * FROM $TGBot->table_name WHERE type=?");
            $fetchAll->execute(['private']);
            $fetchAll = $fetchAll->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchAll as $fetch) {
                if ($TGBot->reply_photo) {
                    $TGBot->sendPhoto($fetch['chat_id'], $TGBot->reply_photo_file_id, $TGBot->reply_photo_caption);
                }
                if ($TGBot->reply_to_message_text) {
                    $TGBot->sendMessage($fetch['chat_id'], $TGBot->reply_to_message_text);
                }
                if ($TGBot->reply_video) {
                    $TGBot->sendVideo($fetch['chat_id'], $TGBot->reply_video_file_id, $TGBot->reply_video_caption);
                }
                if ($TGBot->reply_document) {
                    $TGBot->sendDocument($fetch['chat_id'], $TGBot->reply_document_file_id, $TGBot->reply_document_caption);
                }
            }
        }
        $update = $TGBot->pdb->prepare("UPDATE $TGBot->table_name SET action=?, to_update=? WHERE chat_id=?");
        $update->execute(['none', true, $TGBot->chat_id]);
        $TGBot->sendMessage($TGBot->chat_id, 'Done!');
    }
}
