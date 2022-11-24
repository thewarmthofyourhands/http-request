<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use JsonException;

use function Eva\Common\Functions\json_encode;

class JsonRequestMessageBuilder extends HttpRequestMessageBuilder
{
    /**
     * @throws JsonException
     */
    protected function buildBody(): null|string
    {
        if ($this->body !== null) {
            return json_encode($this->body);
        }

        return null;
    }
}
