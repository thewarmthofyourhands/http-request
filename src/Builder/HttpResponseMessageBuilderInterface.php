<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

interface HttpResponseMessageBuilderInterface extends ResponseMessageBuilderInterface
{
    public function addBody(null|array $body): static;
    public function addHeaders(array $headers): static;
    public function setStatusCode(int $statusCode): static;
    public function setProtocolVersion(string $protocolVersion): static;
}
