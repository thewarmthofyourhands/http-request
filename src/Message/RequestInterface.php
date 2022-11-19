<?php

declare(strict_types=1);

namespace Eva\Http\Message;

use Eva\Http\HttpMethodsEnum;

interface RequestInterface extends MessageInterface
{
    public function getUri(): string;
    public function getMethod(): HttpMethodsEnum;
}
