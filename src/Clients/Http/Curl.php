<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

use MiladRahimi\TwitterBot\Utils\HttpQueryBuilder;
use RuntimeException;

class Curl implements Client
{
    /**
     * @var int
     */
    private $timeout = 10;

    /**
     * @inheritDoc
     */
    public function call(Request $request, int $responseType): Response
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->options($request));
        $result = curl_exec($ch);

        if (curl_errno($ch) > 0) {
            $message = curl_error($ch);
            $code = curl_errno($ch);
            curl_close($ch);
            throw new RuntimeException($message, $code);
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        [$headers, $body] = explode("\r\n\r\n", $result, 2);
        curl_close($ch);

        return ResponseFactory::create($responseType, $status, $this->parseHeaders($headers), $body);
    }

    /**
     * Parse http headers to an array of single headers
     *
     * @param string $headers
     *
     * @return array
     */
    private function parseHeaders(string $headers): array
    {
        $list = [];
        foreach (explode("\r\n", $headers) as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $value] = explode(':', $line, 2);
                $list[trim($key)] = trim($value);
            }
        }

        return $list;
    }

    /**
     * Generate curl options for given request
     *
     * @param Request $request
     * @return array
     */
    private function options(Request $request): array
    {
        $options = $this->basicOptions();

        $options[CURLOPT_HTTPHEADER] = $request->headers();
        $options[CURLOPT_URL] = $request->url();

        if ($request->method() == 'GET') {
            $options[CURLOPT_URL] .= '?' . HttpQueryBuilder::build($request->parameters());
        } else {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $request->parameters()
                ? HttpQueryBuilder::build($request->parameters())
                : $request->body();
        }

        if (!in_array($request->method(), ['GET', 'POST'])) {
            $options[CURLOPT_CUSTOMREQUEST] = $request->method();
        }

        return $options;
    }

    /**
     * Get curl default options
     *
     * @return array
     */
    private function basicOptions(): array
    {
        return [
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_USERAGENT => 'https://github.com/miladrahimi/twitter-bot',
            CURLOPT_ENCODING => 'gzip',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @inheritDoc
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }
}
