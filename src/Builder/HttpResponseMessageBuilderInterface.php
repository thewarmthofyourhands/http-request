<?php

declare(strict_types=1);

namespace Lilith\Http\Builder;

use Lilith\Http\Message\ResponseInterface;

interface HttpResponseMessageBuilderInterface
{
    public function addBody(null|array $body): static;
    public function addHeaders(array $headers): static;
    public function addFiles(array $files): static;
    public function setStatusCode(int $statusCode): static;
    public function setProtocolVersion(string $protocolVersion): static;
    public function build(): ResponseInterface;
}
