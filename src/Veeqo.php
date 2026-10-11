<?php

namespace TremulantTech\Veeqo;

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
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\MemoryStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
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
use TremulantTech\Veeqo\Resources\Orders;

/**
 * One connector per Veeqo account (ADR 0005).
 */
class Veeqo extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasRateLimits;

    // 1 request + 3 retries, 1s/2s/4s (ADR 0001).
    public ?int $tries = 4;

    public ?int $retryInterval = 1000;

    public ?bool $useExponentialBackoff = true;

    // Every Veeqo 429 lacks Retry-After, so the plugin would lock the credential for 60s and throw (ADR 0009).
    protected bool $detectTooManyAttempts = false;

    final private function __construct(
        private readonly TokenAuthenticator|HeaderAuthenticator $authenticator,
        #[\SensitiveParameter] string $credential,
        ?RateLimitStore $rateLimitStore,
    ) {
        $this->rateLimitStore = $rateLimitStore;
        $this->rateLimitingEnabled = $rateLimitStore !== null;
        // The store only ever sees a hash of the secret.
        $this->limiterPrefix = 'veeqo:'.hash('sha256', $credential);
    }

    private string $limiterPrefix;

    /** An Appstore app's credential for one Veeqo account. */
    public static function withAccessToken(#[\SensitiveParameter] string $accessToken, ?RateLimitStore $rateLimitStore = null): static
    {
        return new static(new TokenAuthenticator($accessToken), $accessToken, $rateLimitStore);
    }

    /** A private integration's credential. */
    public static function withApiKey(#[\SensitiveParameter] string $apiKey, ?RateLimitStore $rateLimitStore = null): static
    {
        return new static(new HeaderAuthenticator($apiKey, 'x-api-key'), $apiKey, $rateLimitStore);
    }

    public function orders(): Orders
    {
        return new Orders($this);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.veeqo.com';
    }

    protected function defaultAuth(): TokenAuthenticator|HeaderAuthenticator
    {
        return $this->authenticator;
    }

    // 429 is never processed by Veeqo, so any method may resend it; anything else may have landed, so only GET does.
    public function handleRetry(FatalRequestException|RequestException $exception, Request $request): bool
    {
        if ($exception instanceof RequestException && $exception->getResponse()->status() === 429) {
            return true;
        }

        return $request->getMethod() === Method::GET
            && ($exception instanceof FatalRequestException || $exception->getResponse()->serverError());
    }

    public function getRequestException(Response $response, ?Throwable $senderException): VeeqoRequestException
    {
        $class = match (true) {
            in_array($response->status(), [400, 422], true) => ValidationFailed::class,
            $response->status() === 401 => Unauthorized::class,
            $response->status() === 403 => Forbidden::class,
            $response->status() === 404 => NotFound::class,
            $response->status() === 429 => RateLimited::class,
            $response->serverError() => ServerError::class,
            default => VeeqoRequestException::class,
        };

        return new $class($response, previous: $senderException);
    }

    /** @return list<Limit> */
    protected function resolveLimits(): array
    {
        // A fixed window can pass 2N across a boundary: 50 stays under Veeqo's observed ~56 burst (ADR 0009).
        return [Limit::allow(25)->everySeconds(5)->sleep()];
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        // Only reached when a store was given; rate limiting is disabled otherwise.
        return $this->rateLimitStore ?? new MemoryStore;
    }

    protected function getLimiterPrefix(): string
    {
        return $this->limiterPrefix;
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

            // List endpoints return a bare JSON array, so a short page means the end.
            protected function isLastPage(Response $response): bool
            {
                $items = $response->json();

                return ! is_array($items) || count($items) < $this->perPageLimit;
            }

            /** @return array<mixed> */
            protected function getPageItems(Response $response, Request $request): array
            {
                $items = $response->json();

                return is_array($items) ? $items : [];
            }
        };
    }
}
