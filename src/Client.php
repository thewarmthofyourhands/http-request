<?php

declare(strict_types=1);

namespace Eva\Http;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\RequestSender\RequestSenderInterface;
use Eva\Http\RequestSender\RequestSender;

class Client implements ClientInterface
{
    use HttpMethodsTrait;

    public function __construct(protected RequestSenderInterface $requestSender = new RequestSender()) {}

    final public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->requestSender->send($request);
    }
}
