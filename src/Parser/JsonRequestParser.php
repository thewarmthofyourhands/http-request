<?php

declare(strict_types=1);

namespace Eva\Http\Parser;

use JsonException;
use Eva\Http\Message\RequestInterface;

use function Eva\Common\Functions\json_decode;

class JsonRequestParser extends HttpRequestParser
{
    /**
     * @throws JsonException
     */
    public static function parseBody(RequestInterface $request): array
    {
        return json_decode($request->getBody(),true);
    }
}
