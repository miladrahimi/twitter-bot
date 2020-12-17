<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

interface Response
{
    /**
     * Get http status code
     *
     * @return int
     */
    public function status(): int;

    /**
     * Get http headers
     *
     * @return array
     */
    public function headers(): array;

    /**
     * Get http body
     *
     * @return mixed
     */
    public function body();

    /**
     * Get content (parsed body)
     *
     * @return array
     */
    public function content(): array;

    /**
     * Convert response to string (JSON)
     *
     * @return string
     */
    public function __toString(): string;
}
