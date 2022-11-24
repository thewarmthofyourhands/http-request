<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\Message\Response;
use Eva\Http\Message\ResponseInterface;
use Eva\HttpKernel\HttpProtocolVersionEnum;

class HttpResponseMessageBuilder implements HttpResponseMessageBuilderInterface
{
    public function __construct(
        protected array $headers = [],
        protected null|array $body = null,
        protected int $statusCode = 200,
        protected HttpProtocolVersionEnum $protocolVersion = HttpProtocolVersionEnum::HTTP_1_1,
    ) {}

    public function addBody(null|array $body): static
    {
        if (null === $this->body) {
            $this->body = $body;
        } else {
            $this->body += $body;
        }

        return $this;
    }

    public function addHeaders(array $headers): static
    {
        $this->headers += $headers;

        return $this;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setProtocolVersion(HttpProtocolVersionEnum $httpProtocolVersionEnum): static
    {
        $this->protocolVersion = $httpProtocolVersionEnum;

        return $this;
    }

    protected function buildBody(): null|string
    {
        if (null !== $this->body) {
            return http_build_query($this->body);
        }

        return null;
    }

    public function build(): ResponseInterface
    {
        return new Response(
            $this->statusCode,
            $this->headers,
            $this->buildBody(),
            $this->protocolVersion
        );
    }

    public function clear(): void
    {
        $this->statusCode = 200;
        $this->protocolVersion = '1.1';
        $this->body = null;
        $this->headers = [];
    }
}
