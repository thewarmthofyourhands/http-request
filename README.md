Native Request
================

The library provides interaction over the HTTP protocol

The basis is a model of Http packets.
However, it does not support psr and should not be used at all.

The library consists of:
1. HTTP message builders for both request and response
2. Parsers that allow you to quickly pull out of HTTP messages
   data in a format that is easy to process
3. RequestSender - implementation of sending and receiving messages (low-level sending via socket is used)
4. A simple Client and a wrapper either for simple native requests or for creating full-fledged Request services
(Examples are in the example folder)

### Simple example:
````
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$request = new Eva\Http\Message\Request(
    \Eva\Http\HttpMethodsEnum::GET,
    'https://www.google.com',
    [
        'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJlNjc4ZjIzMzQ3ZTM0MTBkYjdlNjg3Njc4MjNiMmQ3MCI'
    ]
);
$client = new \Eva\Http\Client();
$response = $client->sendRequest($request);

if ($response->getStatusCode() === 200) {
    echo $response->getBody();
} else {
    throw new RuntimeException($response->getBody());
}
````

### Using HttpRequestMessageBuilder 
(usually for building query, body, json and etc):
````
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$httpRequestMessageBuilder = new \Eva\Http\Builder\HttpRequestMessageBuilder;
$httpRequestMessageBuilder
    ->addMethod(\Eva\Http\HttpMethodsEnum::GET)
    ->addUrl('https://www.google.com')
    ->addHeaders([
        'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJlNjc4ZjIzMzQ3ZTM0MTBkYjdlNjg3Njc4MjNiMmQ3MCI'
    ]);
$request = $httpRequestMessageBuilder->build();
$client = new \Eva\Http\Client();
$response = $client->sendRequest($request);

if ($response->getStatusCode() === 200) {
    echo $response->getBody();
} else {
    throw new RuntimeException($response->getBody());
}
````

### Using HttpMethodsTrait:
````
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = new \Eva\Http\Client();
$response = $client->get('https://www.google.com', [
    'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJqdGkiOiJlNjc4ZjIzMzQ3ZTM0MTBkYjdlNjg3Njc4MjNiMmQ3MCI',
]);

if ($response->getStatusCode() === 200) {
    echo $response->getBody();
} else {
    throw new RuntimeException($response->getBody());
}
````
