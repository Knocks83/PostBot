<?php
// Imgur, create your credentials here: https://api.imgur.com/oauth2/addclient
$client_id = 'Insert your Client ID here';

// SauceNAO, you can create a API key here: https://saucenao.com/user.php?page=search-api
$api_key = 'Insert your API Key here';
$db = [999];   // DBs list separated with a comma (use 999 for all websites)

// Telegram
$fpam = 'Insert your FPAM key here'; // You can find this starting the bot https://t.me/EasyTGBot
$chatID = 'Insert the destination chat ID here';  // Chat where the bot will forward the photos
$botToken = 'Insert the bot token here'; // Get it on https://t.me/BotFather
$fromWho = [58141179];  // Insert here the people allowed to use the bot, separated with a comma (58141179, anotherID, anotherOne)
$logChat = '0000';  // Insert here the chat ID of a chat you want to use as log

// Miscellaneous
$check_output = false;  // Check or not the IDs returned from the query
$dirPath = 'https://www.example.org/AdvTGBot/'; // Directory to find the BOT index.php (ex. www.example.com/AdvTGBot/)
