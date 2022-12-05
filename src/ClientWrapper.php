<?php

declare(strict_types=1);

namespace Eva\Http;

use Eva\Http\Builder\HttpRequestMessageBuilderInterface;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\ResponseParserInterface;

class ClientWrapper extends AbstractClientWrapper
{
    protected const BASE_URL = null;
    protected const DEFAULT_QUERY = null;
    protected const DEFAULT_HEADERS = null;
    protected const DEFAULT_BODY = null;

    public function __construct(
        ClientInterface $client,
        protected HttpRequestMessageBuilderInterface $httpRequestMessageBuilder,
        protected ResponseParserInterface $responseParser,
    ) {
        parent::__construct($client);
        $this->completeBaseValues();
    }

    protected function completeBaseValues(): void {}
    protected function completeDefaultValues(): void {}

    protected function request(RequestInterface $request): mixed
    {
        $response = $this->sendRequest($request);

        return $this->parseResponse($response);
    }

    protected function parseResponse(ResponseInterface $response): mixed
    {
        return $this->responseParser->parseBody($response);
    }
}
