# Veeqo PHP SDK

A PHP and Laravel client for the [Veeqo API](https://developers.veeqo.com/api), built on [Saloon](https://docs.saloon.dev).

## Get Started

> **Requires PHP 8.4+**

Install the package with Composer:

```bash
composer require tremulanttech/veeqo-php-sdk
```

Add your API key to `.env`:

```env
VEEQO_API_KEY=your-api-key
```

In Laravel, resolve `Veeqo` from the container:

```php
use TremulantTech\Veeqo\Veeqo;

$order = app(Veeqo::class)->orders()->get(123);
```

In plain PHP, construct it yourself:

```php
$veeqo = Veeqo::withApiKey('your-api-key');
```

## Configuration

| Variable | Purpose |
|---|---|
| `VEEQO_API_KEY` | Your API key. `Veeqo` is only bound when it is set. |
| `VEEQO_RATE_LIMIT_STORE` | A cache store name (e.g. `redis`) that shares the rate limit across workers. |
| `VEEQO_CLIENT_ID`, `VEEQO_CLIENT_SECRET`, `VEEQO_REDIRECT_URI` | For Appstore apps only. See [Appstore apps](#appstore-apps). |

Publish the config file with `php artisan vendor:publish --tag=veeqo-config`.

## Usage

Fetch one record:

```php
$order = $veeqo->orders()->get(123); // TremulantTech\Veeqo\Responses\Order
```

List records. `list()` walks every page lazily:

```php
use TremulantTech\Veeqo\Enums\OrderStatus;

foreach ($veeqo->orders()->list(status: OrderStatus::AwaitingFulfillment) as $order) {
    // ...
}
```

Create a record from a `Data` class:

```php
use TremulantTech\Veeqo\Data\Orders\CreateOrderData;
use TremulantTech\Veeqo\Data\Orders\LineItemData;

$order = $veeqo->orders()->create(new CreateOrderData(
    channelId: 1,
    deliveryMethodId: 2,
    customerId: 3,
    deliverToId: 4,
    lineItemsAttributes: [new LineItemData(sellableId: 5, pricePerUnit: '9.99', quantity: 1)],
));
```

Every HTTP error throws a subclass of `VeeqoRequestException`:

```php
use TremulantTech\Veeqo\Exceptions\NotFound;

try {
    $veeqo->orders()->get(123);
} catch (NotFound $e) {
    $e->errorMessages(); // ['Not found']
}
```

## Testing

Fake Veeqo with Saloon's `MockClient`, keyed by request class:

```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Requests\Orders\CancelOrder;

$mock = new MockClient([
    CancelOrder::class => MockResponse::make(status: 204),
]);

$veeqo = Veeqo::withApiKey('test-key')->withMockClient($mock);
$veeqo->orders()->cancel(123);

$mock->assertSent(CancelOrder::class);
```

## Vocabulary

SDK names follow Veeqo's wire terms, which often differ from the words in Veeqo's docs:

| Veeqo docs | SDK | Glossary |
|---|---|---|
| Store | `channels()`, `Channel` | Channel |
| Company | `currentCompany()`, `Company` | Veeqo account |
| Bundle | `kits()`, `Kit` | Kit |
| Bundle Content | `kitContents()`, `KitContent` | Kit content |
| Property | `productProperties()`, `ProductProperty` | Product property |
| Properties (on a product), Remove Property From Product | `productPropertySpecifics()`, `ProductPropertySpecific` | |
| Purchase Order Line Item | `purchaseOrderProductVariants()`, `PurchaseOrderProductVariant` | |
| Email Purchase Order Reminders | `purchaseOrders()->reminder()` | |
| Order Note | `notes()`, `Note` | Note |
| Line Item Notes | `lineItems()->update()` | |
| Returns on Order | `returns()`, `OrderReturn` | Return |
| Bulk Tagging / Bulk Untagging | `bulkTagging()->create()` / `->delete()` | |
| Purchase Shipping Labels | `shipping()->purchaseLabels()`, `CreatedShipment` | |
| Retrieve Shipping Labels | `labels()->get()` | |
| Shipping Rates | `rates()`, `Rate` | |
| View, Retrieve, Show | `get()` | |

## Unverified resources

These are typed from Veeqo's doc examples only, so every field except `id` is nullable: `channelSellables()`, `notes()`, `kits()`, `kitContents()`, `productProperties()`, `productPropertySpecifics()`, `purchaseOrders()`, `purchaseOrderProductVariants()`, `suppliers()`, the `PaymentData` input of `payments()`, `rates()`, `trackingEvents()`, and the `CreatedShipment` returned by `shipments()->create()` and `shipping()->purchaseLabels()`.

## Appstore apps

`VeeqoOAuth` exchanges a Veeqo account's grant for a permanent access token. Set the three OAuth variables and resolve it in Laravel, or use `new VeeqoOAuth($clientId, $clientSecret, $redirectUri)`. You own the routes and token storage, for example:

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

    // Throws InvalidStateException unless the state matches.
    $token = $oauth->getAccessToken($request->string('code')->toString(), $request->query('state'), session()->pull('veeqo_state'))
        ->getAccessToken();

    $company = Veeqo::withAccessToken($token)->currentCompany()->get();

    VeeqoAccount::updateOrCreate(['company_id' => $company->id], ['access_token' => encrypt($token)]);

    return redirect('/');
});
```

An `Unauthorized` (401) on a stored token means that account revoked your app.

## License

MIT. See [LICENSE](LICENSE).
