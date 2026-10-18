<?php

namespace TremulantTech\Veeqo;

use Saloon\Contracts\Authenticator;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;
use TremulantTech\Veeqo\Exceptions\Forbidden;
use TremulantTech\Veeqo\Exceptions\NotFound;
use TremulantTech\Veeqo\Exceptions\RateLimited;
use TremulantTech\Veeqo\Exceptions\ServerError;
use TremulantTech\Veeqo\Exceptions\Unauthorized;
use TremulantTech\Veeqo\Exceptions\ValidationFailed;
use TremulantTech\Veeqo\Exceptions\VeeqoRequestException;

class Veeqo extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    // 1 request + 3 retries, backing off 1s, 2s, 4s; callers tune these per connector or request (ADR 0001).
    // Proactive limiting is opt-in via saloonphp/rate-limit-plugin (ADR 0009, not wired yet).
    public ?int $tries = 4;

    public ?int $retryInterval = 1000;

    public ?bool $useExponentialBackoff = true;

    // One connector per Veeqo account, named by credential kind (ADR 0005).
    final private function __construct(private readonly Authenticator $credential) {}

    public static function withApiKey(#[\SensitiveParameter] string $apiKey): static
    {
        return new static(new HeaderAuthenticator($apiKey, 'x-api-key'));
    }

    public static function withAccessToken(#[\SensitiveParameter] string $accessToken): static
    {
        return new static(new TokenAuthenticator($accessToken));
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.veeqo.com';
    }

    protected function defaultAuth(): Authenticator
    {
        return $this->credential;
    }

    public function getRequestException(Response $response, ?Throwable $senderException): VeeqoRequestException
    {
        $status = $response->status();

        return match (true) {
            $status === 400, $status === 422 => new ValidationFailed($response, previous: $senderException),
            $status === 401 => new Unauthorized($response, previous: $senderException),
            $status === 403 => new Forbidden($response, previous: $senderException),
            $status === 404 => new NotFound($response, previous: $senderException),
            $status === 429 => new RateLimited($response, previous: $senderException),
            $response->serverError() => new ServerError($response, previous: $senderException),
            default => new VeeqoRequestException($response, previous: $senderException),
        };
    }

    // A 429 was refused outright, so any method can be resent. A 5xx or connection failure on a write
    // may already have taken effect, so only GET retries those (ADR 0001).
    public function handleRetry(FatalRequestException|RequestException $exception, Request $request): bool
    {
        if ($exception instanceof RequestException && $exception->getResponse()->status() === 429) {
            return true;
        }

        return $request->getMethod() === Method::GET
            && ($exception instanceof FatalRequestException || $exception->getResponse()->serverError());
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
