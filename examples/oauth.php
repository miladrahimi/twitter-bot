<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);

$response = $bot->oauth('oauth/request_token', [
    'oauth_callback' => 'https://bp.miladrahimi.com/accounts/callback'
]);

if ($response->status() == 200) {
    $token = $response->content()['oauth_token'];
    $tokenSecret = $response->content()['oauth_token_secret'];

    $url = $bot->oauthUrl($token);
    echo $url, $token, $tokenSecret;

    // Redirect user to this url
}

