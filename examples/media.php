<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$bot->setOAuthToken(TOKEN, TOKEN_SECRET);

$file = __DIR__ . '/files/pink-floyd.jpg';
$mediaResponse = $bot->upload($file, 'POST', 'media/upload.json', [
    'media_category' => 'tweet_image',
    'media_type' => 'image/jpeg',
]);

$response = $bot->api('POST', 'statuses/update.json', [
    'status' => 'Hello from bot!',
    'media_ids' => $mediaResponse->content()['media_id'],
]);

print_r($response->content());