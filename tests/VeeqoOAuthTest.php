<?php

use Saloon\Contracts\OAuthAuthenticator;
use Saloon\Exceptions\InvalidStateException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\OAuth2\GetAccessTokenRequest;
use TremulantTech\Veeqo\Exceptions\Unauthorized;
use TremulantTech\Veeqo\VeeqoOAuth;

function veeqoOAuth(): VeeqoOAuth
{
    return new VeeqoOAuth('client-id', 'client-secret', 'https://app.test/veeqo/callback');
}

it('builds the authorize url on app.veeqo.com with a generated state', function () {
    $oauth = veeqoOAuth();

    $url = $oauth->getAuthorizationUrl();
    parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

    expect(strtok($url, '?'))->toBe('https://app.veeqo.com/oauth/authorize')
        ->and($query)->toMatchArray([
            'response_type' => 'code',
            'client_id' => 'client-id',
            'redirect_uri' => 'https://app.test/veeqo/callback',
        ])
        ->and($query['state'])->toBeString()->toHaveLength(32)
        ->and($oauth->getState())->toBe($query['state']);
});

it('generates a fresh state for every authorize url', function () {
    $oauth = veeqoOAuth();

    $oauth->getAuthorizationUrl();
    $first = $oauth->getState();
    $oauth->getAuthorizationUrl();

    expect($oauth->getState())->not->toBe($first);
});

it('exchanges the code on api.veeqo.com for a permanent access token', function () {
    $mock = new MockClient([GetAccessTokenRequest::class => MockResponse::make(['access_token' => 'token-123', 'token_type' => 'Bearer'])]);

    $authenticator = veeqoOAuth()->withMockClient($mock)->getAccessToken('the-code', 'state-1', 'state-1');

    expect($authenticator)->toBeInstanceOf(OAuthAuthenticator::class)
        ->and($authenticator->getAccessToken())->toBe('token-123')
        ->and($authenticator->getRefreshToken())->toBeNull()
        ->and($authenticator->getExpiresAt())->toBeNull();
    $mock->assertSentCount(1);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/oauth/token'
        && $response->getPendingRequest()->body()?->all() === [
            'grant_type' => 'authorization_code',
            'code' => 'the-code',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'redirect_uri' => 'https://app.test/veeqo/callback',
        ]);
});

it('refuses to exchange the code unless the state matches', function (?string $state, ?string $expectedState) {
    $mock = new MockClient([GetAccessTokenRequest::class => MockResponse::make(['access_token' => 'token-123'])]);

    expect(fn () => veeqoOAuth()->withMockClient($mock)->getAccessToken('the-code', $state, $expectedState))
        ->toThrow(InvalidStateException::class);
    $mock->assertNothingSent();
})->with([
    'mismatched' => ['state-1', 'state-2'],
    'lost from the session' => ['state-1', null],
    'missing from the callback' => [null, 'state-1'],
    'both empty' => ['', ''],
]);

it('throws the sdk exception when the exchange fails', function () {
    $mock = new MockClient([GetAccessTokenRequest::class => MockResponse::make(['error' => 'invalid_grant'], 401)]);

    expect(fn () => veeqoOAuth()->withMockClient($mock)->getAccessToken('expired-code', 'state-1', 'state-1'))
        ->toThrow(Unauthorized::class);
});
