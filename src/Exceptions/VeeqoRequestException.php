<?php

namespace TremulantTech\Veeqo\Exceptions;

use Saloon\Exceptions\Request\RequestException;

/**
 * Any HTTP error response; statuses without a subclass land here.
 */
class VeeqoRequestException extends RequestException implements VeeqoException
{
    /**
     * Veeqo's error bodies, flattened: {"error_messages": [..] | ".."} or Rails' {"status", "error"}.
     *
     * @return list<string>
     */
    public function errorMessages(): array
    {
        $body = json_decode($this->getResponse()->body(), true);

        if (! is_array($body)) {
            return [];
        }

        $messages = $body['error_messages'] ?? $body['error'] ?? [];

        return array_values(array_filter(is_array($messages) ? $messages : [$messages], is_string(...)));
    }
}
