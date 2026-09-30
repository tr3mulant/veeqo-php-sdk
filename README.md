# Veeqo PHP SDK

PHP SDK for the [Veeqo API](https://developers.veeqo.com/api), built on [Saloon](https://docs.saloon.dev) v4.

```bash
composer require irongateenterprises/veeqo-php-sdk
```

## Plain PHP

```php
use IronGate\Veeqo\Veeqo;
use IronGate\Veeqo\Requests\Orders\GetOrder;
use IronGate\Veeqo\Requests\Orders\ListOrders;

$veeqo = new Veeqo('your-api-key');

$order = $veeqo->send(new GetOrder(123))->json();

// Walks every page (page_size 100) lazily.
foreach ($veeqo->paginate(new ListOrders(['status' => 'awaiting_fulfillment']))->items() as $order) {
    // ...
}
```

## Laravel

Auto-discovered. Set `VEEQO_API_KEY` in `.env`, then inject or resolve `Veeqo`:

```php
app(Veeqo::class)->send(new GetOrder(123));
```

Publish the config with `php artisan vendor:publish --tag=veeqo-config`.

## Rate limits

Veeqo allows 5 requests/second (burst of 100). Requests that get a 429 are retried up to 3 more times with exponential backoff.

## Testing

```bash
vendor/bin/pest
```
