<?php

namespace MiladRahimi\TwitterBot\V1\OAuth1;

use MiladRahimi\TwitterBot\Utils\HttpQueryBuilder;
use MiladRahimi\TwitterBot\Utils\PercentEncoder;

class Authorizer
{
    const VERSION = '1.0';

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var Token|null
     */
    private $token;

    /**
     * Constructor
     *
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    /**
     * Generate API header authorization
     *
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @return string
     */
    public function header(string $method, string $url, array $parameters): string
    {
        $header = 'OAuth ';

        foreach ($this->authFields($method, $url, $parameters) as $name => $value) {
            $header .= PercentEncoder::encode($name) . '="' . PercentEncoder::encode($value) . '",';
        }

        return rtrim($header, ',');
    }

    /**
     * Generate base string for signature
     *
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $oauthFields
     * @return string
     */
    public function baseString(string $method, string $url, array $parameters, array $oauthFields): string
    {
        return implode('&', PercentEncoder::encode([
            strtoupper($method),
            $url,
            HttpQueryBuilder::build(array_merge($parameters, $oauthFields)),
        ]));
    }

    /**
     * Sign request to provide OAuth authorization header
     *
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @return array
     */
    public function authFields(string $method, string $url, array $parameters): array
    {
        $authFields = [
            'oauth_version' => static::VERSION,
            'oauth_timestamp' => time(),
            'oauth_consumer_key' => $this->consumer->key,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_nonce' => $this->nonce(),
        ];

        if ($this->token) {
            $authFields['oauth_token'] = $this->token->key;
        }

        $authFields['oauth_signature'] = $this->sign(
            $this->baseString($method, $url, $parameters, $authFields),
            $this->consumer->secret,
            $this->token ? $this->token->secret : ''
        );

        return $authFields;
    }

    /**
     * Sign
     *
     * @param string $baseString
     * @param string $consumerSecret
     * @param string $tokenSecret
     * @return string
     */
    public function sign(string $baseString, string $consumerSecret, string $tokenSecret): string
    {
        return base64_encode(hash_hmac(
            'sha1',
            $baseString,
            implode('&', PercentEncoder::encode([$consumerSecret, $tokenSecret])),
            true
        ));
    }

    /**
     * Generate a nonce
     *
     * @return string
     */
    private function nonce(): string
    {
        return md5(microtime() . mt_rand());
    }

    /**
     * @param Token|null $token
     */
    public function setToken(?Token $token): void
    {
        $this->token = $token;
    }
}
