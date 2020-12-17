<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

class JsonResponse extends HttpResponse
{
    /**
     * @inheritDoc
     * @return array
     */
    public function content(): array
    {
        return $this->body() ? json_decode($this->body(), true) : [];
    }
}
