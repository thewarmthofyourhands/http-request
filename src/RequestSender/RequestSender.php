<?php

declare(strict_types=1);

namespace Eva\Http\RequestSender;

use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\Response;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Parser\HttpRequestParser;
use Eva\Http\HttpProtocolVersionEnum;
use RuntimeException;

class RequestSender implements RequestSenderInterface
{
    public function send(RequestInterface $request): ResponseInterface
    {
        [$host, $isSsl, $port, $message] = $this->extractHttpData($request);
        [$httpStatusCode, $headerList, $body, $httpProtocol] = $this->sendHttpBySocket($host, $isSsl, $port, $message);

        return new Response(
            (int) $httpStatusCode,
            $headerList,
            $body,
            HttpProtocolVersionEnum::from($httpProtocol),
        );
    }

    protected function extractHttpData(RequestInterface $request): array
    {
        $uriData = HttpRequestParser::parseUri($request);
        $fullPath = $uriData->getPath().$uriData->getFragment().$uriData->getQuery() ?: '/';
        $host = $uriData->getHost();
        $method = $request->getMethod()->value;
        $headers = $request->getHeaders();
        $protocolVersion = $request->getProtocolVersion()->value;
        $isSsl = $uriData->getScheme() === 'https';
        $port = (int) ($uriData->getPort() ?? ($isSsl ? 443 : 80));

        $message =  "$method $fullPath HTTP/$protocolVersion\r\n";
        $message .= "Host: $host\r\n";

        foreach ($headers as $header => $headerValue) {
            $message .= "$header: $headerValue\r\n";
        }

        $message .= "Connection: Close\r\n";

        if (in_array($request->getBody(), [
            HttpMethodsEnum::PATCH,
            HttpMethodsEnum::POST,
            HttpMethodsEnum::PUT
        ], true)) {
            $contentLength = strlen($request->getBody());
            $body = $request->getBody();

            $message .=
                "Content-Length: $contentLength\r\n"
                . "\r\n"
                ."$body\r\n";
        } else {
            $message .= "\r\n";
        }

        return [$host, $isSsl, $port, $message];
    }

    protected function sendHttpBySocket(string $host, bool $isSsl, int $port, string $message): array
    {
        $hostname = $isSsl ? 'ssl://' . $host : $host;
        $socket = fsockopen($hostname, $port, $errorCode, $errorMessage);

        if (false === $socket) {
            throw new RuntimeException($errorCode . ': '. $errorMessage);
        }

        fwrite($socket, $message);

        $httpStatusString = fgets($socket, 1024);
        $httpStatusString = trim($httpStatusString);
        preg_match('|HTTP/(\d\.\d)\s+(\d+)\s+.*|', $httpStatusString, $match);
        [$httpStatus, $httpProtocol, $httpStatusCode] = $match;
        $headerList = [];

        while (!feof($socket)) {
            $responseLine = fgets($socket, 1024);

            if ($responseLine === "\r\n") {
                break;
            }

            [$header, $headerValue] = explode(':', $responseLine, 2);
            $headerList[$header] = $headerValue;
        }

        $body = null;

        if (false === feof($socket)) {
            $body = '';
            $contentLength = current(array_filter(
                $headerList,
                static fn (string $headerValue, string $header): bool => strtolower($header) === 'content-length',
                ARRAY_FILTER_USE_BOTH,
            ));

            if ($contentLength) {
                for($i = 1; $i <= $contentLength; $i++) {
                    $body .= fgetc($socket);
                }
            } else {
                if (HttpProtocolVersionEnum::tryFrom($httpProtocol) === HttpProtocolVersionEnum::HTTP_1_0) {
                    throw new RuntimeException('Content-length is empty, try to use HTTP 1.1');
                }

                //http 1.1 chunked transfer encoding
                $body = $this->readBodyChunk($socket);
            }

            fclose($socket);
        }

        return [$httpStatusCode, $headerList, $body, $httpProtocol];
    }

    protected function readBodyChunk($socket, null|float $blockLength = null): string
    {
        $body = '';

        $preamble = fgets($socket, 1024);
        $blockLength = (float) hexdec(trim($preamble));

        if ($blockLength === 0.0) {
            return $body;
        }

        for($i = 1.0; $i <= $blockLength; $i++) {
            $body .= fgetc($socket);
        }

        fgets($socket, 2);
        $preamble = fgets($socket, 1024);
        $blockLength = (float) hexdec(trim($preamble));
        $body .= $this->readBodyChunk($socket, $blockLength);

        return $body;
    }
}
