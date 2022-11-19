<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\Message\RequestInterface;

interface RequestMessageBuilderInterface
{
    public function create(): RequestInterface;
    public function clear(): void;
}
