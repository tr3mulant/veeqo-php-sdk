<?php

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\OAuth2\GetAccessTokenRequest;
use Tests\Laravel\TestCase;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Veeqo;
use TremulantTech\Veeqo\VeeqoOAuth;
use TremulantTech\Veeqo\VeeqoServiceProvider;

uses(TestCase::class);

it('binds a connector built from the configured api key', function () {
    registerWithApiKey('laravel-key');
    $mock = new MockClient([MockResponse::make(['id' => 7])]);

    app(Veeqo::class)->withMockClient($mock)->send(new GetOrder(7));

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->headers()->get('x-api-key') === 'laravel-key'
        && $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/7');
});

it('binds the connector as a singleton', function () {
    registerWithApiKey('laravel-key');

    expect(app(Veeqo::class))->toBe(app(Veeqo::class));
});

it('does not bind a connector without an api key', function (?string $apiKey) {
    registerWithApiKey($apiKey);

    expect(app()->bound(Veeqo::class))->toBeFalse();
})->with(['null' => null, 'empty' => '']);

it('rate limits the singleton through the configured cache store', function () {
    registerWithApiKey('laravel-key', 'array');
    app(Veeqo::class)->withMockClient(new MockClient([MockResponse::make(['id' => 7])]))->send(new GetOrder(7));

    expect(Cache::store('array')->get('veeqo:'.hash('sha256', 'laravel-key').':25_every_5'))->toBeString();
});

function registerWithApiKey(?string $apiKey, ?string $rateLimitStore = null): void
{
    config(['veeqo.api_key' => $apiKey, 'veeqo.rate_limit_store' => $rateLimitStore]);
    app()->forgetInstance(Veeqo::class);
    app()->offsetUnset(Veeqo::class);
    app()->register(VeeqoServiceProvider::class, force: true);
}

it('binds an oauth connector singleton built from the configured client', function () {
    registerWithClient('client-id', 'client-secret', 'https://app.test/veeqo/callback');
    $mock = new MockClient([GetAccessTokenRequest::class => MockResponse::make(['access_token' => 'token-123'])]);

    parse_str((string) parse_url(app(VeeqoOAuth::class)->getAuthorizationUrl(), PHP_URL_QUERY), $query);
    app(VeeqoOAuth::class)->withMockClient($mock)->getAccessToken('the-code', 'state-1', 'state-1');

    expect(app(VeeqoOAuth::class))->toBe(app(VeeqoOAuth::class))
        ->and($query)->toMatchArray(['client_id' => 'client-id', 'redirect_uri' => 'https://app.test/veeqo/callback']);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->body()?->get('client_secret') === 'client-secret');
});

it('does not bind an oauth connector unless the client is fully configured', function (?string $clientId, ?string $clientSecret, ?string $redirectUri) {
    registerWithClient($clientId, $clientSecret, $redirectUri);

    expect(app()->bound(VeeqoOAuth::class))->toBeFalse();
})->with([
    'no client id' => [null, 'client-secret', 'https://app.test/veeqo/callback'],
    'empty client id' => ['', 'client-secret', 'https://app.test/veeqo/callback'],
    'no client secret' => ['client-id', null, 'https://app.test/veeqo/callback'],
    'no redirect uri' => ['client-id', 'client-secret', ''],
]);

function registerWithClient(?string $clientId, ?string $clientSecret, ?string $redirectUri): void
{
    config(['veeqo.client_id' => $clientId, 'veeqo.client_secret' => $clientSecret, 'veeqo.redirect_uri' => $redirectUri]);
    app()->forgetInstance(VeeqoOAuth::class);
    app()->offsetUnset(VeeqoOAuth::class);
    app()->register(VeeqoServiceProvider::class, force: true);
}
