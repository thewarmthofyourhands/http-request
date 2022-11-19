<?php

declare(strict_types=1);

namespace Eva\Http\PSR7;

use InvalidArgumentException;
use Eva\Http\PSR7\Interfaces\UriInterface;

class Uri implements UriInterface
{
    private string $schema;
    private string $authority;
    private string $userInfo = '';
    private string $host = '';
    private null|int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function getScheme(): string
    {
        return $this->schema;
    }

    public function getAuthority(): string
    {
        return sprintf(
            '%s%s%s',
            $this->userInfo !== '' ? $this->userInfo . '@' : '',
            $this->host,
            $this->port !== null ? ':' . $this->port : ''
        );
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): null|int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme): static
    {
        $this->schema = $scheme;

        return $this;
    }

    public function withUserInfo(string $user, ?string $password = null): static
    {
        $this->userInfo = $user . ($password ? ':' . $password : '');

        return $this;
    }

    public function withHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function withPort(int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function withPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function withQuery(string $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function withFragment(string $fragment): static
    {
        $this->fragment = $fragment;

        return $this;
    }

    public function __toString(): string
    {
       return sprintf(
           '%s://%s%s%s',
           $this->schema,
           $this->getAuthority(),
           $this->path,
           $this->query,
       );
    }
}
