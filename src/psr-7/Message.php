<?php

declare(strict_types=1);

namespace Eva\Http\PSR7;

use Eva\Http\PSR7\Interfaces\MessageInterface;
use Eva\Http\PSR7\Interfaces\StreamInterface;

class Message implements MessageInterface
{
    protected array $headers = [];
    protected StreamInterface $body;
    private string $protocolVersion = '1.1';

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): static
    {
        $this->protocolVersion = $version;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name];
    }

    public function getHeaderLine(string $name): string
    {
        return $this->headers[$name];
    }

    public function withHeader(string $name, string|array $value): static
    {
        if (is_string($value)) {
            $value = explode(';', $value);
        }

        $this->headers[$name] = $value;

        return $this;
    }

    public function withAddedHeader(string $name, string|array $value): static
    {
        if (is_string($value)) {
            $value = explode(';', $value);
        }

        $this->headers[$name] = $value;

        return $this;
    }

    public function withoutHeader(string $name): static
    {
        unset($this->headers[$name]);

        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $this->body = $body;

        return $this;
    }
}
