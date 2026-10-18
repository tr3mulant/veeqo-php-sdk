<?php

use Saloon\Config;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\MemoryStore;
use TremulantTech\Veeqo\Exceptions\RateLimited;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Veeqo;

it('sleeps out the window once 25 requests are sent within 5 seconds', function (string $constructor) {
    $sleeps = sleepsFor(Veeqo::$constructor('secret', rateLimitStore: new MemoryStore), 26);

    expect($sleeps)->toHaveCount(1)
        ->and($sleeps[0])->toBeGreaterThan(0)->toBeLessThanOrEqual(5);
})->with(['withApiKey', 'withAccessToken']);

it('attaches no limiter without a store', function () {
    expect(sleepsFor(Veeqo::withApiKey('test-key'), 30))->toBe([]);
});

it('keys the window by a hash of the credential, never the credential itself', function () {
    $store = new MemoryStore;
    sleepsFor(Veeqo::withAccessToken('secret-token', rateLimitStore: $store), 1);

    $keys = array_keys($store->getStore());
    expect($keys)->toBe(['veeqo:'.hash('sha256', 'secret-token').':25_every_5'])
        ->and(json_encode($store->getStore()))->not->toContain('secret-token');
});

it('shares one window across connectors on the same credential only', function () {
    $store = new MemoryStore;
    sleepsFor(Veeqo::withApiKey('key-a', rateLimitStore: $store), 25);

    expect(sleepsFor(Veeqo::withApiKey('key-a', rateLimitStore: $store), 1))->toHaveCount(1)
        ->and(sleepsFor(Veeqo::withApiKey('key-b', rateLimitStore: $store), 1))->toBe([]);
});

it('leaves a 429 to the reactive retries without locking the credential', function () {
    $veeqo = Veeqo::withApiKey('test-key', rateLimitStore: new MemoryStore);

    expect(sleepsFor($veeqo, 1, MockResponse::make('', 429)))->toBe([1, 2, 4])
        ->and(sleepsFor($veeqo, 1))->toBe([]);
});

it('lets a subclass override the limits', function () {
    expect(sleepsFor(StrictVeeqo::withApiKey('test-key', rateLimitStore: new MemoryStore), 3))->toHaveCount(1);
});

class StrictVeeqo extends Veeqo
{
    protected function resolveLimits(): array
    {
        return [Limit::allow(2)->everySeconds(5)->sleep()];
    }
}

it('refuses to turn rate limiting on after construction without a store', function () {
    Veeqo::withApiKey('test-key')->useRateLimitPlugin()->withMockClient(new MockClient([MockResponse::make()]))->send(new GetOrder(1));
})->throws(LogicException::class);

beforeEach(fn () => MemoryStore::clear());

// Sends $count requests through $veeqo and returns the sleeps (in seconds) taken along the way.
function sleepsFor(Veeqo $veeqo, int $count, ?MockResponse $response = null): array
{
    $sleeps = [];
    Config::sleepUsing(function (int $microseconds) use (&$sleeps) {
        $sleeps[] = $microseconds / 1_000_000;
    });
    $veeqo->withMockClient(new MockClient(['*' => $response ?? MockResponse::make(['id' => 1])]));

    try {
        for ($i = 0; $i < $count; $i++) {
            try {
                $veeqo->send(new GetOrder(1));
            } catch (RateLimited) {
            }
        }
    } finally {
        Config::sleepUsing(null);
    }

    return $sleeps;
}
