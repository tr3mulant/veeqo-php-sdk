<?php

namespace TremulantTech\Veeqo;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use LogicException;
use Psr\Http\Message\RequestInterface;
use Saloon\Contracts\Authenticator;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;
use TremulantTech\Veeqo\Exceptions\VeeqoRequestException;
use TremulantTech\Veeqo\Resources\Orders;
use TremulantTech\Veeqo\Resources\Returns;

class Veeqo extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasRateLimits;

    /**
     * 1 request + 3 retries, backing off 1s, 2s, 4s; callers tune these per connector or request (ADR 0001).
     */
    public ?int $tries = 4;

    public ?int $retryInterval = 1000;

    public ?bool $useExponentialBackoff = true;

    private readonly string $limiterPrefix;

    private ?TreeMapper $mapper = null;

    /**
     * Private so a connector is only built by credential kind, one per Veeqo account (ADR 0005).
     */
    final private function __construct(
        private readonly Authenticator $credential,
        #[\SensitiveParameter] string $secret,
        ?RateLimitStore $rateLimitStore,
    ) {
        $this->limiterPrefix = 'veeqo:'.hash('sha256', $secret);
        $this->rateLimitStore = $rateLimitStore;
        $this->rateLimitingEnabled = $rateLimitStore !== null;
        $this->detectTooManyAttempts = false;
    }

    public static function withApiKey(#[\SensitiveParameter] string $apiKey, ?RateLimitStore $rateLimitStore = null): static
    {
        return new static(new HeaderAuthenticator($apiKey, 'x-api-key'), $apiKey, $rateLimitStore);
    }

    public static function withAccessToken(#[\SensitiveParameter] string $accessToken, ?RateLimitStore $rateLimitStore = null): static
    {
        return new static(new TokenAuthenticator($accessToken), $accessToken, $rateLimitStore);
    }

    public function orders(): Orders
    {
        return new Orders($this);
    }

    public function returns(): Returns
    {
        return new Returns($this);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.veeqo.com';
    }

    protected function defaultAuth(): Authenticator
    {
        return $this->credential;
    }

    /**
     * A fixed window can pass 2N across a boundary: 25 per 5s caps a burst at 50, under the ~56 observed (ADR 0009).
     */
    protected function resolveLimits(): array
    {
        return [Limit::allow(25)->everySeconds(5)->sleep()];
    }

    protected function getLimiterPrefix(): string
    {
        return $this->limiterPrefix;
    }

    /**
     * Only reached via the plugin's useRateLimitPlugin(): a store is only ever passed at construction (ADR 0009).
     */
    protected function resolveRateLimitStore(): RateLimitStore
    {
        throw new LogicException('Pass rateLimitStore: to Veeqo::withApiKey() or Veeqo::withAccessToken() to enable rate limiting.');
    }

    /**
     * PHP sends a list as key%5B0%5D=; Veeqo reads only the bracket form key%5B%5D=, on every endpoint.
     */
    public function handlePsrRequest(RequestInterface $request, PendingRequest $pendingRequest): RequestInterface
    {
        $uri = $request->getUri();

        return $request->withUri($uri->withQuery((string) preg_replace('/%5B\d+%5D=/', '%5B%5D=', $uri->getQuery())));
    }

    public function getRequestException(Response $response, ?Throwable $senderException): VeeqoRequestException
    {
        return VeeqoRequestException::fromResponse($response, $senderException);
    }

    /**
     * A 429 was refused outright, so any method can be resent. A 5xx or connection failure on a write
     * may already have taken effect, so only GET retries those (ADR 0001).
     */
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

            /**
             * Veeqo list endpoints return a bare JSON array, so a short page means the end.
             */
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

    /**
     * @internal
     *
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     *
     * @throws UnexpectedResponse
     */
    public function hydrate(string $class, Response $response): object
    {
        return $this->map($class, $response->json(), $response);
    }

    /**
     * @internal
     *
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return list<T>
     *
     * @throws UnexpectedResponse
     */
    public function hydrateList(string $class, Response $response): array
    {
        $items = $response->json();

        if (! array_is_list($items)) {
            throw new UnexpectedResponse($response, ['Expected a JSON array']);
        }

        return array_map(fn (mixed $item) => $this->map($class, $item, $response), $items);
    }

    /**
     * @internal
     *
     * @pure
     */
    public static function propertyName(string $wireKey): string
    {
        return lcfirst(str_replace('_', '', ucwords(rtrim($wireKey, '?'), '_')));
    }

    /**
     * @template T of object
     *
     * @param  class-string<T>  $class
     * @return T
     */
    private function map(string $class, mixed $source, Response $response): object
    {
        try {
            return $this->mapper()->map($class, $source);
        } catch (MappingError $error) {
            throw UnexpectedResponse::fromMappingError($response, $error);
        }
    }

    private function mapper(): TreeMapper
    {
        return $this->mapper ??= (new MapperBuilder)
            ->allowSuperfluousKeys()
            ->registerKeyConverter(self::propertyName(...))
            ->registerConverter(
                /**
                 * var_export, because (string) cuts a float to 14 digits (ADR 0007).
                 *
                 * @return numeric-string
                 */
                static fn (int|float $money): string => var_export($money, true),
            )
            ->mapper();
    }
}
