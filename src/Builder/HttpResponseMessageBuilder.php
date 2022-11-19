<?php

declare(strict_types=1);

namespace Lilith\Http\Builder;

use Lilith\Http\Message\Response;
use Lilith\Http\Message\ResponseInterface;

class HttpResponseMessageBuilder implements HttpResponseMessageBuilderInterface
{
    public function __construct(
        protected array $headers = [],
        protected null|array $body = null,
        protected null|array $files = null,
        protected int $statusCode = 200,
        protected string $protocolVersion = '1.1',
    ) {}

    public function addBody(null|array $body): static
    {
        $this->body += $body;

        return $this;
    }

    public function addHeaders(array $headers): static
    {
        $this->headers += $headers;

        return $this;
    }

    public function addFiles(array $files): static
    {
        $this->files += $files;

        return $this;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setProtocolVersion(string $protocolVersion): static
    {
        $this->protocolVersion = $protocolVersion;

        return $this;
    }

    public function build(): ResponseInterface
    {
        return new Response(
            $this->statusCode,
            $this->headers,
            http_build_query($this->body),
            $this->protocolVersion
        );
    }
}
