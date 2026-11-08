<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Exceptions\Forbidden;
use TremulantTech\Veeqo\Exceptions\NotFound;
use TremulantTech\Veeqo\Exceptions\RateLimited;
use TremulantTech\Veeqo\Exceptions\Unauthorized;
use TremulantTech\Veeqo\Exceptions\ValidationFailed;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Responses\Allocation;
use TremulantTech\Veeqo\Responses\AllocationPackage;
use TremulantTech\Veeqo\Responses\Channel;
use TremulantTech\Veeqo\Responses\ChannelSellable;
use TremulantTech\Veeqo\Responses\Company;
use TremulantTech\Veeqo\Responses\Customer;
use TremulantTech\Veeqo\Responses\DeliveryMethod;
use TremulantTech\Veeqo\Responses\Kit;
use TremulantTech\Veeqo\Responses\KitContent;
use TremulantTech\Veeqo\Responses\Note;
use TremulantTech\Veeqo\Responses\Order;
use TremulantTech\Veeqo\Responses\OrderReturn;
use TremulantTech\Veeqo\Responses\Product;
use TremulantTech\Veeqo\Responses\ProductProperty;
use TremulantTech\Veeqo\Responses\ProductPropertySpecific;
use TremulantTech\Veeqo\Responses\ProductPropertySpecificSummary;
use TremulantTech\Veeqo\Responses\Rate;
use TremulantTech\Veeqo\Responses\Shipment;
use TremulantTech\Veeqo\Responses\StockEntry;
use TremulantTech\Veeqo\Responses\Tag;
use TremulantTech\Veeqo\Responses\TrackingEvent;
use TremulantTech\Veeqo\Responses\Warehouse;
use TremulantTech\Veeqo\Veeqo;

/**
 * [Class] means each item of a list body hydrates into Class.
 */
const HYDRATES = [
    'responses/orders.show' => Order::class,
    'responses/orders.list' => [Order::class],
    'responses/orders.list.shipped' => [Order::class],
    'responses/orders.list.bad_date_ignored' => [Order::class],
    'responses/orders.list.unknown_status' => [Order::class],
    'responses/orders.returns' => [OrderReturn::class],
    // Veeqo's doc examples for these list endpoints show a single item.
    'docs/list-all-orders' => Order::class,
    'docs/view-an-order-detail' => Order::class,
    'docs/show-returns-on-order' => OrderReturn::class,
    'docs/create-a-new-order' => Order::class,
    'docs/create-a-new-order-note' => Note::class,
    'docs/create-a-new-allocation' => Order::class,
    'docs/update-allocation-detail' => Allocation::class,
    'docs/update-allocation-package' => AllocationPackage::class,
    'responses/customers.show' => Customer::class,
    'responses/customers.list' => [Customer::class],
    'docs/list-all-customers' => Customer::class,
    'docs/view-customer-detail' => Customer::class,
    'docs/create-a-customer' => Customer::class,
    'docs/update-customer-detail' => Customer::class,
    'responses/channels.list' => [Channel::class],
    'responses/channels.show' => Channel::class,
    'docs/list-all-stores' => [Channel::class],
    'docs/view-store-detail' => Channel::class,
    'docs/create-a-store' => Channel::class,
    'docs/update-store-detail' => Channel::class,
    'docs/createchannelsellable' => ChannelSellable::class,
    'responses/delivery_methods.show' => DeliveryMethod::class,
    'responses/delivery_methods.list' => [DeliveryMethod::class],
    'docs/list-all-delivery-methods' => [DeliveryMethod::class],
    'docs/view-delivery-method' => DeliveryMethod::class,
    'docs/create-a-delivery-method' => DeliveryMethod::class,
    'docs/update-delivery-method-detail' => DeliveryMethod::class,
    'responses/current_company.show' => Company::class,
    'docs/view-company-detail' => Company::class,
    'responses/warehouses.show' => Warehouse::class,
    'responses/warehouses.list' => [Warehouse::class],
    'docs/list-all-warehouses' => [Warehouse::class],
    'docs/view-warehouse-detail' => Warehouse::class,
    'docs/create-a-warehouse' => Warehouse::class,
    'docs/update-warehouse-detail' => Warehouse::class,
    'responses/tags.show' => Tag::class,
    'responses/tags.list' => [Tag::class],
    'docs/list-all-tags' => [Tag::class],
    'docs/view-an-tag-detail' => Tag::class,
    'docs/create-a-new-tag' => Tag::class,
    'responses/products.show' => Product::class,
    'responses/products.list' => [Product::class],
    'docs/list-all-products' => [Product::class],
    'docs/view-product-detail' => Product::class,
    'docs/create-a-new-product' => Product::class,
    'docs/update-product-detail' => Product::class,
    'docs/create-a-new-property' => ProductProperty::class,
    'docs/view-properties' => ProductPropertySpecific::class,
    'docs/update-property-detail' => ProductPropertySpecificSummary::class,
    'docs/retrieve-bundle-detail' => Kit::class,
    'docs/create-a-bundle' => Kit::class,
    'docs/retrieve-bundle-content' => KitContent::class,
    'docs/create-bundle-content' => KitContent::class,
    'docs/update-bundle-content' => KitContent::class,
    'responses/stock_entry.show' => StockEntry::class,
    'docs/show-a-stock-entry' => StockEntry::class,
    'docs/update-a-stock-entry' => StockEntry::class,
    'docs/create-a-shipment' => Shipment::class,
    'docs/purchase-shipping-labels' => Shipment::class,
    'docs/retrieve-shipping-rates' => Rate::class,
    'responses/shipping.tracking_events' => [TrackingEvent::class],
    'docs/view-tracking-events' => [TrackingEvent::class],
];

/**
 * Download bodies, returned unparsed as a StreamInterface (ADR 0004), so they hydrate into nothing.
 */
const STREAMS = ['docs/retrieve-shipping-labels'];

/**
 * JSON:API bodies, unwrapped from `data` before hydrating (ADR 0006).
 */
const JSON_API = ['docs/update-allocation-package', 'docs/createchannelsellable'];

const THROWS = [
    'responses/errors.400.orders.bad_page_size' => ValidationFailed::class,
    'responses/errors.400.shipping.rates' => ValidationFailed::class,
    'responses/errors.401' => Unauthorized::class,
    'responses/errors.403.purchase_orders.list' => Forbidden::class,
    'responses/errors.403.suppliers.list' => Forbidden::class,
    'responses/errors.404.order' => NotFound::class,
    'responses/errors.404.product' => NotFound::class,
    'responses/errors.404.route' => NotFound::class,
    'responses/errors.429' => RateLimited::class,
    'docs/errors.400.purchase-shipping-labels' => ValidationFailed::class,
    'docs/errors.400.retrieve-shipping-rates.example1' => ValidationFailed::class,
    'docs/errors.400.retrieve-shipping-rates.example2' => ValidationFailed::class,
    'docs/errors.422.update-allocation-package' => ValidationFailed::class,
];

/**
 * Fixtures whose resource isn't typed yet. Each resource's ticket moves its fixtures into HYDRATES; the last one deletes this.
 */
const PENDING = [
    'docs/create-a-new-supplier',
    'docs/delete-purchase-order-line-item',
    'docs/email-purchase-order-reminders', 'docs/get-purchase-order',
    'docs/list-all-purchase-orders', 'docs/list-all-suppliers',
    'docs/update-purchase-order',
    'docs/view-a-supplier-detail',
];

function sentFixture(string $name): Response
{
    return veeqo(new MockClient(['*' => fixtureResponse($name)]))->send(new GetOrder(1));
}

/**
 * @param  class-string|array{class-string}  $class
 * @return array{mixed, mixed} the hydrated object or list, and the decoded body
 */
function hydrateFixture(string $name, string|array $class): array
{
    $response = sentFixture($name);
    $veeqo = Veeqo::withApiKey('test-key');

    if (in_array($name, JSON_API, true)) {
        return [$veeqo->hydrateJsonApi($class, $response), Veeqo::unwrapJsonApi($response->json())];
    }

    return [is_array($class) ? $veeqo->hydrateList($class[0], $response) : $veeqo->hydrate($class, $response), $response->json()];
}

/**
 * Null, [] or an array of only those is no evidence of a type, so ADR 0006 omits such keys.
 */
function carriesValue(mixed $value): bool
{
    return is_array($value) ? array_filter($value, carriesValue(...)) !== [] : $value !== null;
}

/**
 * @return list<string>
 */
function unmappedKeys(mixed $body, mixed $hydrated, string $path = '$'): array
{
    if (is_array($hydrated) && is_array($body)) {
        return array_merge([], ...array_map(fn ($i) => unmappedKeys($body[$i] ?? null, $hydrated[$i], "{$path}[{$i}]"), array_keys($hydrated)));
    }
    if (! is_object($hydrated) || $hydrated instanceof DateTimeInterface || $hydrated instanceof UnitEnum || ! is_array($body)) {
        return [];
    }

    $properties = get_object_vars($hydrated);
    $unmapped = [];
    foreach ($body as $key => $value) {
        if (! carriesValue($value)) {
            continue;
        }
        $property = Veeqo::propertyName((string) $key);
        array_push($unmapped, ...(array_key_exists($property, $properties) ? unmappedKeys($value, $properties[$property], "{$path}.{$key}") : ["{$path}.{$key}"]));
    }

    return $unmapped;
}

it('hydrates every success fixture and maps every key that carries a value', function (string $name, string|array $class) {
    [$hydrated, $body] = hydrateFixture($name, $class);

    expect(unmappedKeys($body, $hydrated))->toBe([]);
})->with(array_map(fn ($class, $name) => [$name, $class], HYDRATES, array_keys(HYDRATES)));

it('throws the mapped exception for every error fixture', function (string $name, string $exception) {
    expect(fn () => sentFixture($name))->toThrow($exception);
})->with(array_map(fn ($exception, $name) => [$name, $exception], THROWS, array_keys(THROWS)));

it('accounts for every fixture on disk exactly once', function () {
    $onDisk = array_map(
        fn (string $path) => basename(dirname($path)).'/'.basename($path, '.json'),
        [...glob(__DIR__.'/Fixtures/responses/*.json'), ...glob(__DIR__.'/Fixtures/docs/*.json')],
    );
    $accounted = [...array_keys(HYDRATES), ...array_keys(THROWS), ...STREAMS, ...PENDING];
    sort($onDisk);
    sort($accounted);

    expect($accounted)->toBe($onDisk);
});
