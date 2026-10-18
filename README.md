# Veeqo PHP SDK

PHP SDK for the [Veeqo API](https://developers.veeqo.com/api), built on [Saloon](https://docs.saloon.dev) v4.

```bash
composer require tremulanttech/veeqo-php-sdk
```

## Plain PHP

```php
use TremulantTech\Veeqo\Veeqo;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;

// Private integration: API key, sent as x-api-key.
$veeqo = Veeqo::withApiKey('your-api-key');

// Appstore app: one connector per Veeqo account, with that account's OAuth access token.
$veeqo = Veeqo::withAccessToken($accessToken);

$order = $veeqo->send(new GetOrder(123))->json();

// Walks every page (page_size 100) lazily.
foreach ($veeqo->paginate(new ListOrders(['status' => 'awaiting_fulfillment']))->items() as $order) {
    // ...
}
```

## Laravel

Auto-discovered. Set `VEEQO_API_KEY` in `.env`, then inject or resolve `Veeqo` (it is only bound when the key is set):

```php
app(Veeqo::class)->send(new GetOrder(123));
```

Publish the config with `php artisan vendor:publish --tag=veeqo-config`.

## Errors

Every HTTP error throws a `TremulantTech\Veeqo\Exceptions\VeeqoRequestException`, or one of its subclasses: `ValidationFailed` (400, 422), `Unauthorized` (401), `Forbidden` (403), `NotFound` (404), `RateLimited` (429) and `ServerError` (5xx). `errorMessages()` returns Veeqo's error messages as a list of strings. Connection failures stay Saloon's `FatalRequestException`.

```php
use TremulantTech\Veeqo\Exceptions\NotFound;

try {
    $veeqo->send(new GetOrder(123));
} catch (NotFound $e) {
    $e->errorMessages(); // ['Not found']
}
```

## Retries

Veeqo allows 5 requests/second (burst of 100). A failed request is retried up to 3 times, backing off 1s, 2s, 4s:

- 429 is retried for every method.
- 5xx and connection failures are retried for `GET` only, so a write that may have reached Veeqo is never resent.

Tune with Saloon's `$tries`, `$retryInterval` and `$useExponentialBackoff` on the connector or a request, e.g. `$request->tries = 1` in a queue job that has its own retries.

## Testing

```bash
composer test
composer analyse   # PHPStan level 10, same command CI runs
```
