<?php

namespace TremulantTech\Veeqo\Exceptions;

use CuyZ\Valinor\Mapper\MappingError;
use RuntimeException;
use Saloon\Http\Response;

/**
 * A successful response that doesn't match its data class (ADR 0006). The raw body stays on the response.
 */
class UnexpectedResponse extends RuntimeException implements VeeqoException
{
    /** @var list<string> */
    public readonly array $mismatches;

    public function __construct(public readonly Response $response, MappingError $error)
    {
        $mismatches = [];
        foreach ($error->messages()->errors() as $message) {
            $mismatches[] = $message->path().': '.$message->toString();
        }
        $this->mismatches = $mismatches;

        parent::__construct("Veeqo response doesn't match the expected shape:\n".implode("\n", $mismatches), previous: $error);
    }
}
