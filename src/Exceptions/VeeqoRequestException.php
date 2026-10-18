<?php

namespace TremulantTech\Veeqo\Exceptions;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use Throwable;

class VeeqoRequestException extends RequestException implements VeeqoException
{
    /**
     * Shared by Veeqo and VeeqoOAuth so both map errors the same way (ADR 0001).
     */
    public static function fromResponse(Response $response, ?Throwable $previous = null): self
    {
        $status = $response->status();

        return match (true) {
            $status === 400, $status === 422 => new ValidationFailed($response, previous: $previous),
            $status === 401 => new Unauthorized($response, previous: $previous),
            $status === 403 => new Forbidden($response, previous: $previous),
            $status === 404 => new NotFound($response, previous: $previous),
            $status === 429 => new RateLimited($response, previous: $previous),
            $response->serverError() => new ServerError($response, previous: $previous),
            default => new self($response, previous: $previous),
        };
    }

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
