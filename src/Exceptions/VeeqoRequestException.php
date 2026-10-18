<?php

namespace TremulantTech\Veeqo\Exceptions;

use Saloon\Exceptions\Request\RequestException;

// Thrown for every HTTP error response; statuses without a subclass are thrown as this class.
class VeeqoRequestException extends RequestException implements VeeqoException
{
    /**
     * Veeqo's error body comes in three shapes: {"error_messages": [..]}, {"error_messages": "..."} and {"status", "error"}.
     *
     * @return list<string>
     */
    public function errorMessages(): array
    {
        $body = json_decode($this->getResponse()->body(), true);
        $messages = is_array($body) ? $body['error_messages'] ?? $body['error'] ?? null : null;

        return match (true) {
            is_string($messages) => [$messages],
            is_array($messages) => array_values(array_filter($messages, is_string(...))),
            default => [],
        };
    }
}
