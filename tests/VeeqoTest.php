<?php

use Saloon\Config;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;
use TremulantTech\Veeqo\Veeqo;

it('sends the api key and fetches an order', function () {
    $mock = new MockClient([GetOrder::class => MockResponse::make(['id' => 42])]);

    $order = veeqo($mock)->send(new GetOrder(42))->json();

    expect($order['id'])->toBe(42);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->headers()->get('x-api-key') === 'test-key'
        && $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/42');
});

it('sends a bearer token when built with an access token', function () {
    $mock = new MockClient([GetOrder::class => MockResponse::make(['id' => 42])]);

    Veeqo::withAccessToken('token-123')->withMockClient($mock)->send(new GetOrder(42));

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->headers()->get('Authorization') === 'Bearer token-123'
        && $response->getPendingRequest()->headers()->get('x-api-key') === null);
});

it('can only be built by credential kind', function () {
    expect((new ReflectionMethod(Veeqo::class, '__construct'))->isPrivate())->toBeTrue();

    foreach (['withApiKey', 'withAccessToken'] as $method) {
        $param = (new ReflectionMethod(Veeqo::class, $method))->getParameters()[0];
        expect($param->getAttributes(SensitiveParameter::class))->toHaveCount(1);
    }
});

it('lets a subclass override the base url', function () {
    $mock = new MockClient([GetOrder::class => MockResponse::make(['id' => 42])]);

    ProxiedVeeqo::withApiKey('test-key')->withMockClient($mock)->send(new GetOrder(42));

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://proxy.test/orders/42');
});

it('paginates with page and page_size until a short page', function () {
    $pages = [];
    $mock = new MockClient([ListOrders::class => function (PendingRequest $request) use (&$pages) {
        $pages[] = $request->query()->all();
        $count = $request->query()->get('page') === 1 ? 100 : 3;

        return MockResponse::make(array_fill(0, $count, ['id' => 1]));
    }]);

    $items = veeqo($mock)->paginate(new ListOrders(['status' => 'shipped']))->items();

    expect(iterator_count($items))->toBe(103)
        ->and($pages)->toBe([
            ['status' => 'shipped', 'page' => 1, 'page_size' => 100],
            ['status' => 'shipped', 'page' => 2, 'page_size' => 100],
        ]);
});

// Records attempts and backoff sleeps for one request against a response that always fails the same way.
function retryAttempts(Request $request, MockResponse $response, ?Veeqo $veeqo = null): array
{
    $sleeps = [];
    Config::sleepUsing(function (int $microseconds) use (&$sleeps) {
        $sleeps[] = $microseconds / 1_000_000;
    });
    $attempts = 0;
    $mock = new MockClient(['*' => function () use (&$attempts, $response) {
        $attempts++;

        return $response;
    }]);

    try {
        ($veeqo ?? Veeqo::withApiKey('test-key'))->withMockClient($mock)->send($request);
    } catch (Throwable) {
    } finally {
        Config::sleepUsing(null);
    }

    return ['attempts' => $attempts, 'sleeps' => $sleeps];
}

function writeRequest(Method $method): Request
{
    return new class($method) extends Request
    {
        public function __construct(protected Method $method) {}

        public function resolveEndpoint(): string
        {
            return '/orders/1';
        }
    };
}

it('retries a failing request 3 times with exponential backoff from 1s, ignoring Retry-After', function () {
    expect(retryAttempts(new GetOrder(1), MockResponse::make('', 429, ['Retry-After' => '30'])))
        ->toBe(['attempts' => 4, 'sleeps' => [1, 2, 4]]);
});

it('retries 429 for every method', function (Method $method) {
    expect(retryAttempts(writeRequest($method), MockResponse::make('', 429))['attempts'])->toBe(4);
})->with([Method::POST, Method::PUT, Method::PATCH, Method::DELETE]);

it('retries 5xx and connection failures for GET', function (Closure $response) {
    expect(retryAttempts(new GetOrder(1), $response())['attempts'])->toBe(4);
})->with([
    '500' => fn () => MockResponse::make('', 500),
    '503' => fn () => MockResponse::make('', 503),
    'connection failure' => fn () => connectionFailure(),
]);

it('never resends a write after a 5xx or connection failure', function (Method $method, Closure $response) {
    expect(retryAttempts(writeRequest($method), $response())['attempts'])->toBe(1);
})->with([Method::POST, Method::PUT, Method::PATCH, Method::DELETE])->with([
    '500' => fn () => MockResponse::make('', 500),
    'connection failure' => fn () => connectionFailure(),
]);

it('does not retry other client errors', function (int $status) {
    expect(retryAttempts(new GetOrder(1), MockResponse::make('', $status))['attempts'])->toBe(1);
})->with([400, 401, 403, 404, 422]);

it('returns the response once a retry succeeds', function () {
    $mock = new MockClient([MockResponse::make('', 429), MockResponse::make(['id' => 1])]);

    expect(veeqo($mock)->send(new GetOrder(1))->json('id'))->toBe(1);
});

it('lets the retry policy be tuned on the connector', function () {
    $veeqo = Veeqo::withApiKey('test-key');
    $veeqo->tries = 2;
    $veeqo->retryInterval = 500;
    $veeqo->useExponentialBackoff = false;

    expect(retryAttempts(new GetOrder(1), MockResponse::make('', 429), $veeqo))->toBe(['attempts' => 2, 'sleeps' => [0.5]]);
});

it('lets the retry policy be tuned per request', function () {
    $request = new GetOrder(1);
    $request->tries = 3;
    $request->retryInterval = 100;
    $request->useExponentialBackoff = false;

    expect(retryAttempts($request, MockResponse::make('', 429)))->toBe(['attempts' => 3, 'sleeps' => [0.1, 0.1]]);
});

class ProxiedVeeqo extends Veeqo
{
    public function resolveBaseUrl(): string
    {
        return 'https://proxy.test';
    }
}
