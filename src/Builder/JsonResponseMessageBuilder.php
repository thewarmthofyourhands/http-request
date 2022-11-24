<?php

declare(strict_types=1);

namespace Eva\Http\Builder;

use JsonException;

use function Eva\Common\Functions\json_encode;

class JsonResponseMessageBuilder extends HttpResponseMessageBuilder
{
    /**
     * @throws JsonException
     */
    protected function buildBody(): null|string
    {
        if (null !== $this->body) {
            return json_encode($this->body);
        }

        return null;
    }
}
