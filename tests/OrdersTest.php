<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\Order;
use TremulantTech\Veeqo\Data\Orders\CreateOrderData;
use TremulantTech\Veeqo\Data\Orders\LineItemData;
use TremulantTech\Veeqo\Data\Orders\ShippingAddressData;
use TremulantTech\Veeqo\Data\Orders\UpdateOrderData;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;
use TremulantTech\Veeqo\Requests\Orders\CancelOrder;
use TremulantTech\Veeqo\Requests\Orders\CreateOrder;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;
use TremulantTech\Veeqo\Requests\Orders\UpdateOrder;

// ADR 0006: every success fixture hydrates, and every key it holds is mapped or deliberately unmapped.
dataset('order fixtures', ['orders.show', 'orders.list', 'orders.list.shipped', 'orders.list.bad_date_ignored']);

it('hydrates and fully maps', function (string $name) {
    $body = fixtureBody($name);
    $request = array_is_list($body) ? ListOrders::class : GetOrder::class;
    $response = veeqo(new MockClient([$request => fixture($name)]))->send($request === GetOrder::class ? new GetOrder(1) : new ListOrders);

    $orders = $response->dto();
    foreach (array_is_list($body) ? $body : [$body] as $i => $json) {
        $order = is_array($orders) ? $orders[$i] : $orders;
        expect($order)->toBeInstanceOf(Order::class)
            ->and(unmappedKeys($json, $order))->toBe([]);
    }
})->with('order fixtures');

it('gets an order with ADR 0007 values', function () {
    $order = veeqo(new MockClient([GetOrder::class => fixture('orders.show')]))->orders()->get(1051);

    expect($order->id)->toBe(1051)
        ->and($order->totalPrice)->toBe('1.11')
        ->and($order->deliveryCost)->toBe('0')
        ->and($order->createdAt)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($order->channel->id)->toBeInt()
        ->and($order->customer)->toBeNull();
});

it('lists every page lazily with typed filters', function () {
    $queries = [];
    $page = fixtureBody('orders.list.shipped');
    $mock = new MockClient([ListOrders::class => function (PendingRequest $request) use (&$queries, $page) {
        $queries[] = $request->query()->all();

        return MockResponse::make($request->query()->get('page') === 1 ? array_merge(...array_fill(0, 10, $page)) : $page);
    }]);

    $orders = veeqo($mock)->orders()->list(status: 'shipped', createdAfter: new DateTimeImmutable('2026-01-01T00:00:00+00:00'));

    expect(iterator_to_array($orders))->toHaveCount(110)->each->toBeInstanceOf(Order::class)
        ->and($queries[0])->toBe(['created[after]' => '2026-01-01T00:00:00+00:00', 'status' => 'shipped', 'page' => 1, 'page_size' => 100])
        ->and($queries)->toHaveCount(2);
});

it('throws UnexpectedResponse when a response does not match its class', function () {
    $body = fixtureBody('orders.show');
    $body['id'] = 'not-an-int';

    $call = fn () => veeqo(new MockClient([GetOrder::class => MockResponse::make($body)]))->orders()->get(1);

    expect($call)->toThrow(UnexpectedResponse::class, 'id');
});

it('creates an order with an enveloped, snake_cased, null-free body', function () {
    $mock = new MockClient([CreateOrder::class => fixture('orders.show')]);

    $order = veeqo($mock)->orders()->create(new CreateOrderData(
        channelId: 1,
        deliveryMethodId: 2,
        lineItemsAttributes: [new LineItemData(sellableId: 3, pricePerUnit: '9.99', quantity: 2)],
        deliverToAttributes: new ShippingAddressData(customerId: 4, firstName: 'Ada'),
        dueDate: new DateTimeImmutable('2026-02-03T00:00:00+00:00'),
    ));

    expect($order)->toBeInstanceOf(Order::class);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->body()->all() === ['order' => [
        'channel_id' => 1,
        'delivery_method_id' => 2,
        'line_items_attributes' => [['sellable_id' => 3, 'price_per_unit' => '9.99', 'quantity' => 2]],
        'deliver_to_attributes' => ['customer_id' => 4, 'first_name' => 'Ada'],
        'due_date' => '2026-02-03T00:00:00+00:00',
    ]]);
});

it('updates and cancels with PUT', function () {
    $mock = new MockClient([UpdateOrder::class => MockResponse::make([], 200), CancelOrder::class => MockResponse::make([], 204)]);

    veeqo($mock)->orders()->update(1, new UpdateOrderData(totalDiscounts: '5'));
    veeqo($mock)->orders()->cancel(1);

    $mock->assertSent(fn ($request, $response) => $request instanceof UpdateOrder
        && $response->getPendingRequest()->body()->all() === ['order' => ['total_discounts' => '5']]);
    $mock->assertSent(fn ($request, $response) => $request instanceof CancelOrder
        && $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/1/cancel');
});
