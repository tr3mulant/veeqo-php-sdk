# Veeqo PHP SDK

PHP SDK for the [Veeqo API](https://developers.veeqo.com/api), built on [Saloon](https://docs.saloon.dev) v4.

```bash
composer require tremulanttech/veeqo-php-sdk
```

## Plain PHP

```php
use TremulantTech\Veeqo\Enums\OrderStatus;
use TremulantTech\Veeqo\Veeqo;

// Private integration: API key, sent as x-api-key.
$veeqo = Veeqo::withApiKey('your-api-key');

// Appstore app: one connector per Veeqo account, with that account's OAuth access token.
$veeqo = Veeqo::withAccessToken($accessToken);

$order = $veeqo->orders()->get(123); // TremulantTech\Veeqo\Responses\Order

// Walks every page (page_size 100) lazily, one request per page.
foreach ($veeqo->orders()->list(status: OrderStatus::AwaitingFulfillment, createdAfter: new DateTimeImmutable('-1 day')) as $order) {
    // ...
}
```

Money is a numeric string (`$order->totalPrice === '12.50'`), date-times are `DateTimeImmutable`. A response that doesn't match its class throws `UnexpectedResponse`, carrying the response and `mismatches()`.

The Saloon requests stay public underneath, for the raw body, headers, page-level control or a filter value the SDK doesn't know yet:

```php
use TremulantTech\Veeqo\Requests\Orders\ListOrders;

$request = new ListOrders(channelIds: [1, 2]);
$request->query()->add('status', 'some_new_status');

foreach ($veeqo->paginate($request) as $page) {
    $page->json();
}
```

## Laravel

Auto-discovered. Set `VEEQO_API_KEY` in `.env`, then inject or resolve `Veeqo` (it is only bound when the key is set):

```php
app(Veeqo::class)->orders()->get(123);
```

Publish the config with `php artisan vendor:publish --tag=veeqo-config`.

## Appstore apps

`VeeqoOAuth` takes a Veeqo account from your authorize redirect to a permanent access token (Veeqo issues no refresh token and no expiry). Storing tokens and the routes are yours. In Laravel, set `VEEQO_CLIENT_ID`, `VEEQO_CLIENT_SECRET` and `VEEQO_REDIRECT_URI` and resolve `VeeqoOAuth`; it is only bound when all three are set. In plain PHP, use `new VeeqoOAuth($clientId, $clientSecret, $redirectUri)`.

```php
use Illuminate\Http\Request;
use TremulantTech\Veeqo\Veeqo;
use TremulantTech\Veeqo\VeeqoOAuth;

Route::get('/veeqo/connect', function (VeeqoOAuth $oauth) {
    $url = $oauth->getAuthorizationUrl();
    session(['veeqo_state' => $oauth->getState()]);

    return redirect($url);
});

Route::get('/veeqo/callback', function (Request $request, VeeqoOAuth $oauth) {
    if ($request->has('error')) {
        return redirect('/')->with('error', 'Veeqo access was declined.');
    }

    // Throws InvalidStateException unless the state matches the one stored in the session. The code is valid for 10 minutes.
    $token = $oauth->getAccessToken($request->string('code')->toString(), $request->query('state'), session()->pull('veeqo_state'))
        ->getAccessToken();

    // The token response doesn't name the account, so ask Veeqo which account the token belongs to.
    $company = Veeqo::withAccessToken($token)->currentCompany()->get();

    VeeqoAccount::updateOrCreate(['company_id' => $company->id], ['access_token' => encrypt($token)]);

    return redirect('/');
});
```

A failed exchange (for example an expired code) throws the SDK exceptions described under [Errors](#errors). If a stored token gets `Unauthorized` (401), that account has revoked your app. Delete the token and ask the account to connect again.

## Errors

Every HTTP error throws a `TremulantTech\Veeqo\Exceptions\VeeqoRequestException`, or one of its subclasses: `ValidationFailed` (400, 422), `Unauthorized` (401), `Forbidden` (403), `NotFound` (404), `RateLimited` (429) and `ServerError` (5xx). `errorMessages()` returns Veeqo's error messages as a list of strings. Connection failures stay Saloon's `FatalRequestException`.

```php
use TremulantTech\Veeqo\Exceptions\NotFound;

try {
    $veeqo->orders()->get(123);
} catch (NotFound $e) {
    $e->errorMessages(); // ['Not found']
}
```

## Retries

Veeqo allows 5 requests/second (burst of 100). A failed request is retried up to 3 times, backing off 1s, 2s, 4s:

- 429 is retried for every method.
- 5xx and connection failures are retried for `GET` only, so a write that may have reached Veeqo is never resent.

Tune with Saloon's `$tries`, `$retryInterval` and `$useExponentialBackoff` on the connector or a request, e.g. `$request->tries = 1` in a queue job that has its own retries.

## Rate limiting

Veeqo's limit belongs to one credential. Many workers sharing one access token will use up the retries, so pass a shared [rate limit store](https://docs.saloon.dev/installable-plugins/handling-rate-limits) (Redis, PSR-16, Laravel cache, ...) to keep all of them to 25 requests per 5 seconds. Once the window is full, a request sleeps until the window resets and never throws:

```php
use Saloon\RateLimitPlugin\Stores\RedisStore;

$veeqo = Veeqo::withAccessToken($accessToken, rateLimitStore: new RedisStore($redis));
```

The store only ever sees a SHA-256 of the credential. Without a store, no limiter runs. In Laravel, set `VEEQO_RATE_LIMIT_STORE` to a cache store name (e.g. `redis`) for the bound `Veeqo`. To change the limits, subclass `Veeqo` and override `resolveLimits()`.

## Development

```bash
vendor/bin/lefthook install   # once: pre-commit hook runs Pint on staged files
composer test
composer analyse   # PHPStan level 10, same command CI runs
composer lint      # Pint (laravel preset) check; vendor/bin/pint fixes
```
