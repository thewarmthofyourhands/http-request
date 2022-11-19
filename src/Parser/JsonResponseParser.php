<?php

declare(strict_types=1);

namespace Eva\Http\Parser;

use JsonException;
use Eva\Http\Message\ResponseInterface;

use function Eva\Common\Functions\json_decode;

class JsonResponseParser implements ResponseParserInterface
{
    /**
     * @throws JsonException
     */
    public static function parseBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody(),true);
    }
}
