<?php

declare(strict_types=1);

namespace Eva\Http;

enum HttpProtocolVersionEnum: string
{
    case HTTP_1_0 = '1.0';
    case HTTP_1_1 = '1.1';
    case HTTP_2_0 = '2.0';
}
