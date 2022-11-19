<?php

declare(strict_types=1);

namespace Eva\Http\Message;

class Uri implements UriInterface
{
    protected readonly array $uri;

    public function __construct(string $uri)
    {
        $this->uri = parse_url($uri);
    }

    public function getScheme(): string
    {
        return $this->uri['scheme'];
    }

    public function getHost(): string
    {
        return $this->uri['host'];
    }

    public function getPort(): null|string
    {
        return $this->uri['port'] ?? null;
    }

    public function getUser(): null|string
    {
        return $this->uri['user'] ?? null;
    }

    public function getPass(): null|string
    {
        return $this->uri['pass'] ?? null;
    }

    public function getPath(): string
    {
        return $this->uri['path'];
    }

    public function getQuery(): null|string
    {
        return $this->uri['query'] ?? null;
    }

    public function getFragment(): null|string
    {
        return $this->uri['fragment'] ?? null;
    }
}
