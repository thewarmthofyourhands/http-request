<?php

declare(strict_types=1);

namespace Eva\Http\Message;

class Response extends Message implements ResponseInterface
{
    public function __construct(
        protected readonly int $statusCode,
        array $headers = [],
        null|string $body = null,
        string $protocolVersion = '1.1',
    ) {
        parent::__construct($headers, $body, $protocolVersion);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
