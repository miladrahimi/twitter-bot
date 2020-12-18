<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$response = $bot->api('GET', 'search/tweets.json', ['q' => '#pink_floyd']);

if ($response->status() == 200) {
    print_r($response->content());
} else {
    echo $response; // JSON
}
