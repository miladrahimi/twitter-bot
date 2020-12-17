<?php

namespace MiladRahimi\TwitterBot\V1\OAuth1;

class Token
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $secret;

    /**
     * Constructor
     *
     * @param string $key
     * @param string $secret
     */
    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }
}
