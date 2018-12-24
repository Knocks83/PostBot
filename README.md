Postbot
============

**All the credits for [AdvTGBot](https://github.com/MattiaBLX/AdvTGBot) go to [MattiaBLX](https://https://github.com/MattiaBLX/)**

## Requirements:
1. PHP7 or newer
2. A **HTTPS** enabled WebServer

## Setup:
First of all you have to clone the Git repository into your server.
If you have Git installed you can use the following command:
```bash
git clone https://github.com/Knocks83/PostBot.git
```
otherwise you can just download it as zip and then put the files inside it into your webserver.

### Set the variables inside `settings.php`
The settings file contains all the things needed to make the bot run, this is a description of every variable:
* client_id: That's the IMGUR client id, don't set it if you're not gonna use the PhotoUpload function (so you already have a URL).
* api_key: That's the SauceNao API key, it's used to make requests to SauceNAO to get photo sauces.
* db: That's an array of the SauceNAO DBs you want to check. By default it is _999_ that means all the databases. You can find a list of the databases in the file `DBs.json`.
* fpam: That's a string that the bot checks to prevent fake requests.
* chatID: That's the ID of the chat where you want to send the photo with the sauce button to. (if it's a channel/private chat you can get it by forwarding a message from the chat/channel to [this](https://t.me/getidsbot) bot)
* botToken: That's the token Telegram uses to identify the bots and allow them access to its functions.
* fromWho: That's an array of the users that can use the bot. The default one (`58141179`) is my ID, to add more IDs you just have to put them inside the square brackets, separating them with a comma. To get your ID send a message to [this](https://t.me/getidsbot) bot.
* check_output: This is a boolean (true/false) that tells the program if it must check the output of the request. Some IDs (like Pixiv) contains more than 1 ID, by default it also takes the other IDs.
* dirPath: This is the url you have to access to to find your index.php file (contained inside the AdvTGBot).

For more informations about the variables read the comments inside the `settings.php` file

### Set Webhook
The webhook is the URL where Telegram sends every update. You just have to start setWebhook once you've set `settings.php`, it'll do it for you.

## Usage:
When an **authorized user** will send a photo to the bot it will find the sauce and forward the photo with the sauce to the chatID you set in the settings.

---

That's all Folks!
For help just [ask me on Telegram](https://t.me/MakeNekosNotNukes)!


This Source Code Form is subject to the terms of the GNU Affero General Public License v3.0. If a copy of the AGPL-3.0 was not distributed with this
file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
