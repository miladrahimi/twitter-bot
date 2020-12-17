<?php

namespace MiladRahimi\TwitterBot\V1\OAuth1;

use MiladRahimi\TwitterBot\Clients\Http\Request as RequestContract;

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
     * Constructor
     *
     * @param Authorizer $authorizer
     * @param string $method
     * @param string $url
     * @param array $parameters
     */
    public function __construct(Authorizer $authorizer, string $method, string $url, array $parameters)
    {
        $this->authorizer = $authorizer;
        $this->method = $method;
        $this->url = $url;
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function headers(): array
    {
        return [
            'Accept: application/json',
            'Authorization: ' . $this->authorizer->header(
                $this->method,
                $this->url,
                $this->parameters
            ),
        ];
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
}
