<?php

declare(strict_types=1);

namespace Eva\Http\Message;

interface UriInterface
{
    public function getScheme(): string;
    public function getHost(): string;
    public function getPort(): null|int;
    public function getUser(): null|string;
    public function getPass(): null|string;
    public function getPath(): null|string;
    public function getQuery(): null|string;
    public function getFragment(): null|string;
}
