<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Enums\OrderStatus;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;
use TremulantTech\Veeqo\Exceptions\VeeqoException;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;
use TremulantTech\Veeqo\Requests\Returns\ListReturns;
use TremulantTech\Veeqo\Responses\Order;
use TremulantTech\Veeqo\Responses\OrderReturn;
use TremulantTech\Veeqo\Responses\WarehouseSummary;

it('gets an order as a typed Order', function () {
    $mock = new MockClient([GetOrder::class => fixtureResponse('responses/orders.show')]);

    $order = veeqo($mock)->orders()->get(1051);

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->id)->toBe(1051)
        ->and($order->totalPrice)->toBe('1.11')
        ->and($order->createdAt->format(DATE_ATOM))->toBe('2020-01-01T00:00:00+00:00')
        ->and($order->channel->warehouse)->toBeInstanceOf(WarehouseSummary::class);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/1051');
});

it('lists orders lazily across pages, sending filters as Veeqo expects', function () {
    $queries = [];
    $mock = new MockClient([ListOrders::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = $request->createPsrRequest()->getUri()->getQuery();
        $page = fixtureResponse('responses/orders.list')->body();
        $orders = json_decode($page, true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_merge(...array_fill(0, 10, $orders)) : $orders);
    }]);

    $orders = veeqo($mock)->orders()->list(
        status: OrderStatus::AwaitingFulfillment,
        channelIds: [11, 12],
        createdAfter: new DateTimeImmutable('2026-01-01 09:30', new DateTimeZone('America/New_York')),
    );

    expect($queries)->toBe([]);
    $orders = iterator_to_array($orders, false);

    expect($orders)->toHaveCount(110)->each->toBeInstanceOf(Order::class)
        ->and($queries)->toBe([
            'status=awaiting_fulfillment&channel_ids%5B%5D=11&channel_ids%5B%5D=12&created%5Bafter%5D=2026-01-01T14%3A30%3A00%2B00%3A00&page=1&page_size=100',
            'status=awaiting_fulfillment&channel_ids%5B%5D=11&channel_ids%5B%5D=12&created%5Bafter%5D=2026-01-01T14%3A30%3A00%2B00%3A00&page=2&page_size=100',
        ]);
});

it('gives page-level control through paginate()', function () {
    $mock = new MockClient([ListOrders::class => fixtureResponse('responses/orders.list.shipped')]);

    $pages = iterator_to_array(veeqo($mock)->paginate(new ListOrders(status: OrderStatus::Shipped)));

    expect($pages)->toHaveCount(1)
        ->and($pages[0]->json())->toHaveCount(10);
});

it('lets a raw query value through for a status the SDK does not know yet', function () {
    $mock = new MockClient([ListOrders::class => fixtureResponse('responses/orders.list.unknown_status')]);
    $request = new ListOrders;
    $request->query()->add('status', 'not_a_status');

    veeqo($mock)->send($request);

    $mock->assertSent(fn ($request, $response) => $response->getPsrRequest()->getUri()->getQuery() === 'status=not_a_status');
});

it('sends every date filter as DATE_ATOM', function () {
    $mock = new MockClient([ListOrders::class => fixtureResponse('responses/orders.list.unknown_status')]);
    $at = new DateTimeImmutable('2026-01-31T00:00:00+00:00');

    iterator_to_array(veeqo($mock)->orders()->list(sinceId: 5, createdAtMin: $at, updatedAtMin: $at, createdBefore: $at, dueAfter: $at, dueBefore: $at, query: 'bob', tags: 'vip', allocatedAt: 3));

    $mock->assertSent(fn ($request) => $request->query()->all() === [
        'since_id' => 5, 'created_at_min' => '2026-01-31T00:00:00+00:00', 'updated_at_min' => '2026-01-31T00:00:00+00:00',
        'created[before]' => '2026-01-31T00:00:00+00:00', 'due[after]' => '2026-01-31T00:00:00+00:00', 'due[before]' => '2026-01-31T00:00:00+00:00',
        'query' => 'bob', 'tags' => 'vip', 'allocated_at' => 3, 'page' => 1, 'page_size' => 100,
    ]);
});

it('lists the returns on an order', function () {
    $mock = new MockClient([ListReturns::class => MockResponse::make([json_decode(fixtureResponse('docs/show-returns-on-order')->body(), true)])]);

    $returns = veeqo($mock)->returns()->list(orderId: 1051);

    expect($returns)->toHaveCount(1)
        ->and($returns[0])->toBeInstanceOf(OrderReturn::class)
        ->and($returns[0]->lineItems[0]->receivedQuantity)->toBe(4);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/1051/returns');
});

it('throws UnexpectedResponse carrying the response and mismatches', function () {
    $mock = new MockClient([GetOrder::class => MockResponse::make(['id' => 'nope'])]);

    try {
        veeqo($mock)->orders()->get(1);
        $this->fail('Expected UnexpectedResponse');
    } catch (UnexpectedResponse $e) {
        expect($e)->toBeInstanceOf(VeeqoException::class)
            ->and($e->getResponse()->json())->toBe(['id' => 'nope'])
            ->and($e->mismatches())->toContain('id: Value \'nope\' is not a valid integer.');
    }
});
