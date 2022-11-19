<?php

declare(strict_types=1);

namespace Eva\Http\Message;

use Eva\Http\HttpMethodsEnum;

class Request extends Message implements RequestInterface
{
    public function __construct(
        protected readonly HttpMethodsEnum $method,
        protected readonly string $uri,
        array $headers = [],
        null|string $body = null,
        string $protocolVersion = '1.1',
    ) {
        parent::__construct($headers, $body, $protocolVersion);
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): HttpMethodsEnum
    {
        return $this->method;
    }
}
