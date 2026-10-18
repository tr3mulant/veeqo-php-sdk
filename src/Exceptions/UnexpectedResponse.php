<?php

namespace TremulantTech\Veeqo\Exceptions;

use CuyZ\Valinor\Mapper\MappingError;
use RuntimeException;
use Saloon\Http\Response;

/**
 * Callers needing the raw body send the request themselves (ADR 0006).
 */
class UnexpectedResponse extends RuntimeException implements VeeqoException
{
    /**
     * @param  list<string>  $mismatches
     */
    public function __construct(private readonly Response $response, private readonly array $mismatches, ?MappingError $previous = null)
    {
        parent::__construct('Veeqo response does not match its data class: '.implode('; ', $mismatches), previous: $previous);
    }

    public static function fromMappingError(Response $response, MappingError $error): self
    {
        $mismatches = [];
        foreach ($error->messages()->errors() as $message) {
            $mismatches[] = "{$message->path()}: {$message->toString()}";
        }

        return new self($response, $mismatches, $error);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Valinor's mismatches, each as "path: message".
     *
     * @return list<string>
     */
    public function mismatches(): array
    {
        return $this->mismatches;
    }
}
