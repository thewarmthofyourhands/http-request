<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use Eva\Http\Message\RequestInterface;

interface RequestMessageBuilderInterface
{
    public function build(): RequestInterface;
    public function clear(): void;
}
