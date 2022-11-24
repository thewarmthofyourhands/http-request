<?php

declare(strict_types=1);

namespace Eva\Http\Parser;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\Uri;
use Eva\Http\Message\UriInterface;

class HttpRequestParser implements RequestParserInterface
{
    public static function parseBody(RequestInterface $request): array
    {
        parse_str($request->getBody(), $requestBody);

        return $requestBody;
    }

    public static function parseUri(RequestInterface $request): UriInterface
    {
        return new Uri($request->getUri());
    }

    public static function parseParams(RequestInterface $request): null|array
    {
        if ($query = static::parseUri($request)->getQuery()) {
            parse_str($query, $params);

            return $params;
        }

        return null;
    }
}
