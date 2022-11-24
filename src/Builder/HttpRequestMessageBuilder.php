<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\Request;
use Eva\Http\Message\RequestInterface;
use Eva\Http\HttpProtocolVersionEnum;

class HttpRequestMessageBuilder implements HttpRequestMessageBuilderInterface
{
    protected null|array $body = null;
    protected null|array $files = null;
    protected array $headers = [];
    protected null|array $query = null;
    protected string $url;
    protected HttpMethodsEnum $method;
    protected HttpProtocolVersionEnum $protocolVersion = HttpProtocolVersionEnum::HTTP_1_1;

    public function addHeaders(array $headers): static
    {
        $this->headers += $headers;

        return $this;
    }

    public function addBody(array $body): static
    {
        if (null === $this->body) {
            $this->body = $body;
        } else {
            $this->body += $body;
        }

        return $this;
    }

    public function addQuery(array $query): static
    {
        if (null === $this->query) {
            $this->query = $query;
        } else {
            $this->query += $query;
        }

        return $this;
    }

    public function addUrl(string $url): static
    {
        if (isset($this->url)) {
            $this->url .= $url;
        } else {
            $this->url = $url;
        }

        return $this;
    }

    public function addMethod(HttpMethodsEnum $method): static
    {
        $this->method = $method;

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

    protected function buildUrl(): string
    {
        $url = $this->url;

        if ($this->query !== null) {
            $url .= '?' . http_build_query($this->query);
        }

        return $url;
    }

    public function build(): RequestInterface
    {
        $method = $this->method;
        $url = $this->buildUrl();
        $body = $this->buildBody();
        $requestMessage = new Request($method, $url, $this->headers, $body, $this->protocolVersion);
        $this->clear();

        return $requestMessage;
    }

    public function clear(): void
    {
        $this->body = null;
        $this->headers = [];
        $this->query = null;
        unset($this->url, $this->method);
        $this->protocolVersion = HttpProtocolVersionEnum::HTTP_1_1;
    }
}
