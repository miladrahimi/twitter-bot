<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

interface Client
{
    /**
     * Make an HTTP request
     *
     * @param Request $request
     * @param int $responseType
     * @return Response
     */
    public function call(Request $request, int $responseType): Response;

    /**
     * Get http timeout in seconds
     *
     * @return int
     */
    public function getTimeout(): int;

    /**
     * Set http timeout in seconds
     *
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void;
}
