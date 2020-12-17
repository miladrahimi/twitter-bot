<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$response = $bot->request('GET', 'search/tweets.jsond', ['q' => '#pink_floyd']);

if ($response->status() == 200) {
    print_r($response->content());
} else {
    echo $response; // JSON
}
