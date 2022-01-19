<?php

namespace MiladRahimi\TwitterBot\V1;

use MiladRahimi\TwitterBot\Clients\Http\Client;
use MiladRahimi\TwitterBot\Clients\Http\Curl;
use MiladRahimi\TwitterBot\Clients\Http\Response;
use MiladRahimi\TwitterBot\Clients\Http\ResponseTypes;
use MiladRahimi\TwitterBot\V1\OAuth1\Authorizer;
use MiladRahimi\TwitterBot\V1\OAuth1\Consumer;
use MiladRahimi\TwitterBot\V1\OAuth1\Token;

class TwitterBot
{
    private static $apiUrl = 'https://api.twitter.com';
    private static $uploadUrl = 'https://upload.twitter.com';
    private static $apiVersion = '1.1';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Authorizer
     */
    private $authorizer;

    /**
     * Create an instance with default dependencies injected
     *
     * @param string $consumerKey
     * @param string $consumerSecret
     * @return static
     */
    public static function create(string $consumerKey, string $consumerSecret): self
    {
        return new self(
            new Curl(),
            new Authorizer(new Consumer($consumerKey, $consumerSecret))
        );
    }

    /**
     * Constructor.
     *
     * @param Client $client
     * @param Authorizer $authorizer
     */
    public function __construct(Client $client, Authorizer $authorizer)
    {
        $this->client = $client;
        $this->authorizer = $authorizer;
    }

    /**
     * Set OAuth token for calling authorized APIs
     *
     * @param string $token
     * @param string $tokenSecret
     */
    public function setOAuthToken(string $token, string $tokenSecret): void
    {
        $this->authorizer->setToken(new Token($token, $tokenSecret));
    }

    /**
     * Make a request to the given Twitter OAuth endpoint
     *
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @return Response
     */
    public function oauth(string $method, string $path, array $parameters = []): Response
    {
        $url = sprintf('%s/%s', static::$apiUrl, $path);

        return $this->call($method, $url, $parameters, [], ResponseTypes::QUERY_STRING);
    }

    /**
     * Generate OAuth URL for browser navigation
     *
     * @param string $oauthToken
     * @return string
     */
    public function oauthUrl(string $oauthToken): string
    {
        return sprintf('%s/%s?%s', self::$apiUrl, 'oauth/authorize', http_build_query([
            'oauth_token' => $oauthToken,
        ]));
    }

    /**
     * Make a request to the given Twitter API
     *
     * @param string $method
     * @param string $path
     * @param array $parameters
     * @return Response
     */
    public function api(string $method, string $path, array $parameters = []): Response
    {
        $url = sprintf('%s/%s/%s', static::$apiUrl, static::$apiVersion, $path);

        return $this->call($method, $url, $parameters);
    }

    /**
     * Make a JSON request to the given Twitter API
     *
     * @param string $method
     * @param string $path
     * @param array $body
     * @return Response
     */
    public function apiJson(string $method, string $path, array $body = []): Response
    {
        $url = sprintf('%s/%s/%s', static::$apiUrl, static::$apiVersion, $path);

        return $this->call($method, $url, [], $body);
    }

    /**
     * Upload a media to the given Twitter API
     *
     * @param string $method
     * @param string $path
     * @param string $file
     * @param array $parameters
     * @return Response
     */
    public function upload(string $file, string $method, string $path, array $parameters = []): Response
    {
        $url = sprintf('%s/%s/%s', static::$uploadUrl, static::$apiVersion, $path);

        $parameters['media_data'] = base64_encode(file_get_contents($file));

        return $this->call($method, $url, $parameters);
    }

    /**
     * Call the given Twitter endpoint
     *
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $body
     * @param int $responseType
     * @return Response
     */
    private function call(
        string $method,
        string $url,
        array $parameters = [],
        array $body = [],
        int $responseType = ResponseTypes::JSON
    ): Response
    {
        return $this->client->call(
            new Request($this->authorizer, $method, $url, $parameters, $body),
            $responseType
        );
    }

    public static function getApiUrl(): string
    {
        return self::$apiUrl;
    }

    public static function setApiUrl(string $apiUrl): void
    {
        self::$apiUrl = $apiUrl;
    }

    public static function getUploadUrl(): string
    {
        return self::$uploadUrl;
    }

    public static function setUploadUrl(string $uploadUrl): void
    {
        self::$uploadUrl = $uploadUrl;
    }

    public static function getApiVersion(): string
    {
        return self::$apiVersion;
    }

    public static function setApiVersion(string $apiVersion): void
    {
        self::$apiVersion = $apiVersion;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
