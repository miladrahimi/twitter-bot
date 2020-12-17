<?php

namespace MiladRahimi\TwitterBot\Clients\Http;

use RuntimeException;

class ResponseFactory
{
    /**
     * Create an appropriate response instance based on given type
     *
     * @param int $type
     * @param int $status
     * @param array $headers
     * @param $body
     * @return Response
     */
    public static function create(int $type, int $status, array $headers, $body): Response
    {
        switch ($type) {
            case ResponseTypes::HTTP:
                return new HttpResponse($status, $headers, $body);
            case ResponseTypes::JSON:
                return new JsonResponse($status, $headers, $body);
            case ResponseTypes::QUERY_STRING:
                return new QueryStringResponse($status, $headers, $body);
            default:
                throw new RuntimeException('Unsupported Response Type');
        }
    }
}
