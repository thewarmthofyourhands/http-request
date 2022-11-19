<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\HttpMethodsEnum;
use Eva\Http\Message\Request;
use Eva\Http\Message\RequestInterface;

class HttpRequestMessageBuilder implements HttpRequestMessageBuilderInterface
{
    protected null|array $body = null;
    protected null|array $files = null;
    protected array $headers = [];
    protected null|array $query = null;
    protected string $url;
    protected HttpMethodsEnum $method;
    protected string $protocolVersion = '1.1';

    public function addHeaders(array $headers): static
    {
        $this->headers += $headers;

        return $this;
    }

    public function addBody(array $body): static
    {
        $this->body += $body;

        return $this;
    }

    public function addFiles(array $files): static
    {
        $this->files += $files;

        return $this;
    }

    public function addQuery(array $query): static
    {
        $this->query += $query;

        return $this;
    }

    public function addUrl(string $url): static
    {
        $this->url .= $url;

        return $this;
    }

    public function addMethod(HttpMethodsEnum $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function create(): RequestInterface
    {
        $method = $this->method;
        $url = $this->url;
        $body = $this->body;

        if ($this->query !== null) {
            $url .= '?' . http_build_query($this->query);
        }

        if ($this->body !== null) {
            $body = http_build_query($this->body);
        }

//        if ($this->files !== null) {
//            //@TODO multiple/form-data
//        }

        $requestMessage = new Request($method, $url, $this->headers, $body, $this->protocolVersion);
        $this->clear();

        return $requestMessage;
    }

    public function clear(): void
    {
        $this->body = null;
        $this->files = null;
        $this->headers = [];
        $this->query = null;
        unset($this->url, $this->method);
        $this->protocolVersion = '1.1';
    }
}
