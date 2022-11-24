<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\Message\ResponseInterface;

interface ResponseMessageBuilderInterface
{
    public function build(): ResponseInterface;
    public function clear(): void;
}
