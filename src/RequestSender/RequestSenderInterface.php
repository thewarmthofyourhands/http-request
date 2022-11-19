<?php

declare(strict_types=1);

namespace Eva\Http\RequestSender;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;

interface RequestSenderInterface
{
    public function send(RequestInterface $request): ResponseInterface;
}
