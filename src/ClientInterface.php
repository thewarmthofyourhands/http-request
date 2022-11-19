<?php

declare(strict_types=1);

namespace Eva\Http;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;

interface ClientInterface
{
//    public function request(RequestInterface $request): ResponseInterface;
    public function sendRequest(RequestInterface $request): ResponseInterface;
}
