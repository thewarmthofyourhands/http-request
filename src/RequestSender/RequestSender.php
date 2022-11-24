<?php

declare(strict_types=1);

namespace Eva\Http\RequestSender;

use CurlHandle;
use Eva\Http\Message\RequestInterface;
use Eva\Http\Message\Response;
use Eva\Http\Message\ResponseInterface;
use Eva\Http\HttpMethodsEnum;
use Eva\Http\Parser\HttpRequestParser;

class RequestSender implements RequestSenderInterface
{
    //@TODO Реализовать условный и частичный get
    /**
     * @throws CurlRequestException
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $handle = curl_init($request->getUri());
        $noResponseBodyMode = in_array($request->getMethod(), [HttpMethodsEnum::HEAD, HttpMethodsEnum::TRACE], true);

        $headers = [];
        $headerParser = static function (CurlHandle $curl, string $header) use (&$headers) {
            $headerList = explode(':', $header, 2);

            if (count($headerList) < 2) {
                return strlen($header);
            }

            $headers[strtolower(trim($headerList[0]))] = trim($headerList[1]);

            return strlen($header);
        };

        $options = [
            CURLOPT_CUSTOMREQUEST => $request->getMethod()->name,
            CURLOPT_URL => $request->getUri(),
            CURLOPT_HEADEROPT => $request->getHeaders(),
            CURLOPT_HTTP_VERSION => $this->getCurlProtocolVersion($request->getProtocolVersion()),
            CURLOPT_HEADERFUNCTION => $headerParser,
            CURLOPT_RETURNTRANSFER => true,
        ];

        if ($request->getBody() !== null) {
            $options[CURLOPT_POSTFIELDS] = $request->getBody();
        }

        if ($noResponseBodyMode) {
            $options[CURLOPT_NOBODY] = true;
            $options[CURLOPT_RETURNTRANSFER] = false;
        }

        curl_setopt_array($handle, $options);
        $responseBody = curl_exec($handle);
        $responseCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        if ($noResponseBodyMode) {
            $responseBody = null;
        }

        if (curl_errno($handle) !== 0) {
            throw new CurlRequestException('Error curl: ' . curl_error($handle));
        }

        unset($handle);

        return new Response($responseCode, $headers, $responseBody);
    }

    public function sendSocket(RequestInterface $request): ResponseInterface
    {
        $uriData = HttpRequestParser::parseUri($request);
        $fullPath = $uriData->getPath().$uriData->getFragment().$uriData->getQuery() ?: '/';
        $host = $uriData->getHost();
        $scheme = $uriData->getScheme();
        $method = $request->getMethod()->value;
        $headers = $request->getHeaders();
        $ssl = $scheme === 'https';
        $port = $uriData->getPort() ?? ($ssl ? 443 : 80);
        $port = (int) $port;
        $protocolVersion = $request->getProtocolVersion();
        $message =  "$method $fullPath HTTP/$protocolVersion\r\n";
        $message .= "Host: $host\r\n";

        foreach ($headers as $header => $headerValue) {
            $message .= "$header: $headerValue\r\n";
        }

        $message .= "Connection: Close\r\n";

        if (in_array($request->getMethod(), [
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

        $hostname = $ssl ? 'ssl://' . $host : $host;
        $socket = fsockopen($hostname, $port, $errno, $errstr);

        if (false === $socket) {
            throw new \RuntimeException($errno . ': '. $errstr);
        }

        fwrite($socket, $message);

        $httpStatusString = fgets($socket, 4096);
        $httpStatusString = trim($httpStatusString);
        preg_match('|HTTP/(\d\.\d)\s+(\d+)\s+.*|',$httpStatusString,$match);
        $httpProtocol = $match[1];
        $httpStatus = $match[2];
        $headerList = [];

        while (!feof($socket)) {
            $responseLine = fgets($socket, 4096);
            if ($responseLine === "\r\n") {
                break;
            }
            [$header, $headerValue] = explode(':', $responseLine, 2);
            $headerList[$header] = $headerValue;
        }

        $body = '';

        $contentLength = current(array_filter(
            $headerList,
            static fn (string $headerValue, string $header): bool => strtolower($header) === 'content-length',
            ARRAY_FILTER_USE_BOTH
        ));

        if ($contentLength) {
            for($i = 1; $i <= $contentLength; $i++) {
                $body .= fgetc($socket);
            }
        } else {
            //http 1.1 сhunked transfer encoding
            $body = $this->readBodyChunk($socket);
        }

        fclose($socket);
        return new Response(
            (int) $httpStatus,
            $headerList,
            $body,
            $httpProtocol
        );
    }

    protected function readBodyChunk($socket, null|float $blockLength = null): string
    {
        if (null === $blockLength) {
            $preamble = fgets($socket, 1024);
            $blockLength = (float) hexdec(trim($preamble));
        }

        $body = '';

        for($i = 1.0; $i <= $blockLength; $i++) {
            $body .= fgetc($socket);
        }

        $preamble = fgets($socket, 1024);
        $blockLength = (float) hexdec(trim($preamble));

        if (0.0 !== $blockLength) {
            $body .= $this->readBodyChunk($socket, $blockLength);
        }

        return $body;
    }

    protected function getCurlProtocolVersion(string $protocolVersion): int
    {
        return match ($protocolVersion) {
            '1.0' => CURL_HTTP_VERSION_1_0,
            '2.0' => CURL_HTTP_VERSION_2_0,
            default => CURL_HTTP_VERSION_1_1,
        };
    }
}
