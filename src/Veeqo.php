<?php

namespace IronGate\Veeqo;

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class Veeqo extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    // Veeqo allows 5 req/s with a burst bucket of 100; a 429 means back off and retry.
    // ponytail: reactive retry only, add saloonphp/rate-limit-plugin if bulk jobs keep hitting 429s.
    public ?int $tries = 4;

    public ?int $retryInterval = 1000;

    public ?bool $useExponentialBackoff = true;

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl = 'https://api.veeqo.com',
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator($this->apiKey, 'x-api-key');
    }

    public function handleRetry(FatalRequestException|RequestException $exception, Request $request): bool
    {
        return $exception instanceof FatalRequestException
            || $exception->getResponse()->status() === 429;
    }

    public function paginate(Request $request): PagedPaginator
    {
        return new class(connector: $this, request: $request) extends PagedPaginator
        {
            protected ?int $perPageLimit = 100;

            protected function applyPagination(Request $request): Request
            {
                $request->query()->merge(['page' => $this->page, 'page_size' => $this->perPageLimit]);

                return $request;
            }

            // Veeqo list endpoints return a bare JSON array, so a short page means the end.
            protected function isLastPage(Response $response): bool
            {
                return count($response->json()) < $this->perPageLimit;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json();
            }
        };
    }
}
