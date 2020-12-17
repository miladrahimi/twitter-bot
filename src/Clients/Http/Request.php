<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

interface Request
{
    /**
     * @return array
     */
    public function headers(): array;

    /**
     * @return string
     */
    public function method(): string;

    /**
     * @return string
     */
    public function url(): string;

    /**
     * @return array
     */
    public function parameters(): array;

    /**
     * @return mixed
     */
    public function body();
}
