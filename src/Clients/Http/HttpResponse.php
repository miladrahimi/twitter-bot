<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

class HttpResponse implements Response
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var mixed
     */
    private $body;

    /**
     * Constructor
     *
     * @param int $status
     * @param array $headers
     * @param $body
     */
    public function __construct(int $status, array $headers, $body)
    {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @inheritDoc
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function content(): array
    {
        return [
            'body' => $this->body,
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'headers' => $this->headers(),
            'content' => $this->content(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
