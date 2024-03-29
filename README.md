[![Latest Stable Version](https://poser.pugx.org/miladrahimi/twitter-bot/v/stable)](https://packagist.org/packages/miladrahimi/twitter-bot)
[![Total Downloads](https://poser.pugx.org/miladrahimi/twitter-bot/downloads)](https://packagist.org/packages/miladrahimi/twitter-bot)
[![License](https://poser.pugx.org/miladrahimi/twitter-bot/license)](https://packagist.org/packages/miladrahimi/twitter-bot)

# Twitter Bot

This package is a basic Twitter bot that handles authentication, HTTP requests and responses, and other primitive requirements.
It doesn't focus on any specific Twitter API.
Instead, it provides a tool that makes calling Twitter APIs much simpler.

## Installation

Install [Composer](https://getcomposer.org) and run the following command in your project's root directory:

```bash
composer require miladrahimi/twitter-bot "1.*"
```

## Documentation

### Getting Started

The snippet below demonstrates how to create an instance of Twitter Bot.
It requires your consumer key (API Key) and consumer secret (API Key Secret).
You can get them from your Twitter Developer Portal.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
// Your bot is ready!
```

### Public Endpoints

Public endpoints work without authentication and user tokens.
You can call public endpoints like the following sample.
This sample shows how to call the search API.
It searches for tweets that contain the given hashtag.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$response = $bot->api('GET', 'search/tweets.json', ['q' => '#pink_floyd']);

if ($response->status() == 200) {
    print_r($response->content());
} else {
    echo $response; // JSON
}
```

### Authenticated Endpoints

Authenticated endpoints work only with authenticated user tokens.
A user token includes a user token and a user token secret.

If you want to call Twitter APIs on behalf of yourself,
you can get your token and token secret from your Twitter Developer Portal.
If you need to call Twitter APIs on behalf of other users,
you must authorize them before, as mentioned later in this documentation.

This sample shows how to call the update status API and tweet the given text.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$bot->setOAuthToken(TOKEN, TOKEN_SECRET);
$response = $bot->api('POST', 'statuses/update.json', ['status' => 'Hello from bot!']);

print_r($response->content());
```

### JSON APIs

The example below demonstrates how to call APIs with JSON body (like the direct message API).
This sample shows how to call sending direct message API and send the given message to the given user.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);
$bot->setOAuthToken(TOKEN, TOKEN_SECRET);
$response = $bot->apiJson('POST', 'direct_messages/events/new.json', [
    'event' => [
        'type' => 'message_create',
        'message_create' => [
            'target' => [
                'recipient_id' => 666,
            ],
            'message_data' => [
                'text' => 'Hello from bot!',
            ]
        ]
    ]
]);

print_r($response->content());
```

### Uploading Media

You might need to upload media.
For example, if you want to tweet a photo, you need to upload it first.
The following example illustrates how to upload a photo and tweet it.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

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
```

### Authorize Users (Login with Twitter)

This section explains how to authorize users to get their tokens and token secrets.
It's an implementation of "Login with Twitter" indeed.

#### Request for Token and Redirection Link

First, you must request a user token and confirm your callback URL implicitly
(Consider setting your callback URL in your Twitter Developer Portal).
Then you can redirect the user to the Twitter website to approve your request (token).

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);

$response = $bot->oauth('POST', 'oauth/request_token', [
    'oauth_callback' => 'https://your-app.com/twitter/callback',
]);

if ($response->status() == 200) {
    $token = $response->content()['oauth_token'];
    $tokenSecret = $response->content()['oauth_token_secret'];
    
    // Generate twitter redirection url
    $url = $bot->oauthUrl($token);

    // Redirect user to $url
}
```

#### Twitter Callback

When a user approves your request (token) on the Twitter website, Twitter redirects him to your callback URL.
In the callback URL, you can verify the token.
Then you can use the token for calling Twitter APIs on behalf of the user.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);

$response = $bot->oauth('POST', 'oauth/access_token', [
    'oauth_token' => $_REQUEST['oauth_token'],
    'oauth_verifier' => $_REQUEST['oauth_verifier'],
]);

print_r($response->content()); // oauth_token, oauth_token_secret, screen_name, ...
```

### Timeout

In default, the HTTP (cURL) timeout is 10 seconds.
You can set your desired timeout (in seconds) like the following example.

```php
use MiladRahimi\TwitterBot\V1\TwitterBot;

$bot = TwitterBot::create(CONSUMER_KEY, CONSUMER_SECRET);

$bot->getClient()->setTimeout(13);
```

## License

PhpRouter is initially created by [Milad Rahimi](https://miladrahimi.com)
and released under the [MIT License](http://opensource.org/licenses/mit-license.php).
