<?php

use MiladRahimi\TwitterBot\V1\TwitterBot;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/keys.php';

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$bot->setOAuthToken(TOKEN, TOKEN_SECRET);
$response = $bot->json('POST', 'direct_messages/events/new.json', [
    'event' => [
        'type' => 'message_create',
        'message_create' => [
            'target' => [
                'recipient_id' => 1133118263137714177,
            ],
            'message_data' => [
                'text' => 'Hello from bot!',
            ]
        ]
    ]
]);

print_r($response->content());
