<?php

declare(strict_types=1);

namespace Eva\Http\Message;

use Eva\Http\HttpProtocolVersionEnum;

class Response extends Message implements ResponseInterface
{
    public function __construct(
        protected readonly int $statusCode,
        array $headers = [],
        null|string $body = null,
        HttpProtocolVersionEnum $protocolVersion = HttpProtocolVersionEnum::HTTP_1_1,
    ) {
        parent::__construct($headers, $body, $protocolVersion);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
