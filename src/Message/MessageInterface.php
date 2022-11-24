<?php

declare(strict_types=1);

namespace Eva\Http\Message;

use Eva\HttpKernel\HttpProtocolVersionEnum;

interface MessageInterface
{
    public function getHeaders(): array;
    public function getHeader(string $name): string;
    public function getBody(): null|string;
    public function getProtocolVersion(): HttpProtocolVersionEnum;
}
