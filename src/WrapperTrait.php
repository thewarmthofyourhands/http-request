<?php

declare(strict_types=1);

namespace Eva\Http;

use Eva\Http\Builder\HttpRequestMessageBuilderInterface;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\Parser\ResponseParserInterface;

trait WrapperTrait
{
    public function __construct(
        protected ClientInterface $client,
        protected HttpRequestMessageBuilderInterface $httpRequestMessageBuilder,
        protected ResponseParserInterface $responseParser,
    ) {
        parent::__construct($client);
        $this->completeBaseValues();
    }

    protected function completeBaseValues(): void {}
    protected function completeDefaultValues(): void {}

    protected function parseResponse(ResponseInterface $response): array
    {
        return $this->responseParser->parseBody($response);
    }

    protected function request(RequestInterface $request): array
    {
        $this->preRequest($request);
        $response = $this->sendRequest($request);
        $this->postRequest($request, $response);

        return $this->parseResponse($response);
    }

    protected function preRequest(RequestInterface $request): void {}
    protected function postRequest(RequestInterface $request, ResponseInterface $response): void {}
}
