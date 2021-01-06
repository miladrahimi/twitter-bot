<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$bot->setOAuthToken(TOKEN, TOKEN_SECRET);
$response = $bot->api('GET', 'search/tweets.json', [
    'q' => '@france_soir',
    'count' => 1,
]);

if ($response->status() == 200 && count($response->content()['statuses']) > 0) {
    $tweet = $response->content()['statuses'][0];
    $response = $bot->api('POST', "statuses/retweet/{$tweet['id']}.json", [
        'id' => $tweet['id'],
    ]);
    print_r($response);
} else {
    echo $response; // JSON
}
