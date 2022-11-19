<?php

declare(strict_types=1);

namespace Eva\Http\Parser;

use Eva\Http\Message\ResponseInterface;

interface ResponseParserInterface
{
    public static function parseBody(ResponseInterface $response): mixed;
}
