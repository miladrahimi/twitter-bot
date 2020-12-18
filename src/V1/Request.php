<?php

namespace MiladRahimi\TwitterBot\V1;

use MiladRahimi\TwitterBot\Clients\Http\Request as RequestContract;
use MiladRahimi\TwitterBot\V1\OAuth1\Authorizer;

class Request implements RequestContract
{
    /**
     * @var Authorizer
     */
    private $authorizer;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $body;

    /**
     * Constructor
     *
     * @param Authorizer $authorizer
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $body
     */
    public function __construct(
        Authorizer $authorizer,
        string $method,
        string $url,
        array $parameters,
        array $body
    )
    {
        $this->authorizer = $authorizer;
        $this->method = $method;
        $this->url = $url;
        $this->parameters = $parameters;
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function headers(): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: ' . $this->authorizer->header(
                $this->method,
                $this->url,
                $this->parameters
            ),
        ];

        if ($this->body) {
            $headers[] = 'Content-type: application/json';
        } else {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }

        return $headers;
    }

    /**
     * @inheritDoc
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function body()
    {
        return json_encode($this->body);
    }
}
