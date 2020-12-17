<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$bot->setOAuthToken(TOKEN, TOKEN_SECRET);
$response = $bot->request('POST', 'statuses/update.json', ['status' => 'Hello from bot!']);

print_r($response->content());
