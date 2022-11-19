<?php

declare(strict_types=1);

namespace Lilith\Http\Message;

interface UriInterface
{
    public function getScheme(): string;
    public function getHost(): string;
    public function getPort(): null|string;
    public function getUser(): null|string;
    public function getPass(): null|string;
    public function getPath(): string;
    public function getQuery(): null|string;
    public function getFragment(): null|string;
}
