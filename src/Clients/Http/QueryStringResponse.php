<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

class QueryStringResponse extends HttpResponse
{
    /**
     * @inheritDoc
     */
    public function content(): array
    {
        $content = [];
        parse_str($this->body(), $content);

        return $content;
    }
}
