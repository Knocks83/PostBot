<?php
/*This Source Code Form is subject to the terms of the Mozilla Public
  License, v. 2.0. If a copy of the MPL was not distributed with this
  file, You can obtain one at http://mozilla.org/MPL/2.0/.*/

require '../settings.php';
include 'TGBot.php';
$TGBot = new TGBot(file_get_contents('php://input'), $fpam, $_GET['fpam'], $_GET['token']);
$TGBot->SecTest();
include 'conf.php';
include 'mysql.php';
include 'postgres.php';
include 'commands.php';
