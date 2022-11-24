<?php

declare(strict_types=1);

namespace Eva\Http\Parser;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\UriInterface;

interface RequestParserInterface
{
    public static function parseBody(RequestInterface $request): mixed;
    public static function parseUri(RequestInterface $request): UriInterface;
    public static function parseParams(RequestInterface $request): null|array;
}
