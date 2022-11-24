<?php

declare(strict_types=1);

namespace Eva\Http\Message;

use Eva\Http\HttpProtocolVersionEnum;

class Message implements MessageInterface
{
    public function __construct(
        protected readonly array $headers = [],
        protected readonly null|string $body = null,
        protected HttpProtocolVersionEnum $protocolVersion = HttpProtocolVersionEnum::HTTP_1_1,
    ) {}

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): string
    {
        return $this->headers[$name];
    }

    public function getBody(): null|string
    {
        return $this->body;
    }

    public function getProtocolVersion(): HttpProtocolVersionEnum
    {
        return $this->protocolVersion;
    }
}
