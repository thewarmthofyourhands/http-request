<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\HttpMethodsEnum;
use Eva\Http\HttpProtocolVersionEnum;

interface HttpRequestMessageBuilderInterface extends RequestMessageBuilderInterface
{
    public function addQuery(array $query): static;
    public function addHeaders(array $headers): static;
    public function addBody(array $body): static;
    public function addUrl(string $url): static;
    public function addMethod(HttpMethodsEnum $method): static;
    public function setProtocolVersion(HttpProtocolVersionEnum $httpProtocolVersionEnum): static;
}
