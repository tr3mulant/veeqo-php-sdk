<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\LineItems\UpdateLineItemData;
use TremulantTech\Veeqo\Data\Notes\CreateNoteData;
use TremulantTech\Veeqo\Data\Orders\AddressData;
use TremulantTech\Veeqo\Data\Orders\CancelOrderData;
use TremulantTech\Veeqo\Data\Orders\CreateOrderData;
use TremulantTech\Veeqo\Data\Orders\LineItemData;
use TremulantTech\Veeqo\Data\Orders\NoteData;
use TremulantTech\Veeqo\Data\Orders\PaymentData;
use TremulantTech\Veeqo\Data\Orders\UpdateOrderData;
use TremulantTech\Veeqo\Enums\OrderStatus;
use TremulantTech\Veeqo\Enums\PaymentType;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;
use TremulantTech\Veeqo\Exceptions\VeeqoException;
use TremulantTech\Veeqo\Requests\LineItems\UpdateLineItem;
use TremulantTech\Veeqo\Requests\Notes\CreateNote;
use TremulantTech\Veeqo\Requests\Orders\CancelOrder;
use TremulantTech\Veeqo\Requests\Orders\CreateOrder;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;
use TremulantTech\Veeqo\Requests\Orders\ListOrders;
use TremulantTech\Veeqo\Requests\Orders\UpdateOrder;
use TremulantTech\Veeqo\Requests\Returns\ListReturns;
use TremulantTech\Veeqo\Responses\Note;
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

it('creates an order, sending the envelope, wire keys and DATE_ATOM dates and dropping nulls', function () {
    $mock = new MockClient([CreateOrder::class => fixtureResponse('docs/create-a-new-order')]);

    $order = veeqo($mock)->orders()->create(new CreateOrderData(
        channelId: 1,
        deliveryMethodId: 2,
        lineItemsAttributes: [
            new LineItemData(sellableId: 3, pricePerUnit: '9.99', quantity: 2, taxRate: 0.2),
            new LineItemData(sellableId: 4, pricePerUnit: '1', quantity: 1),
        ],
        customerId: 5,
        number: null,
        dueDate: new DateTimeImmutable('2026-01-31 09:30', new DateTimeZone('America/New_York')),
        deliverToAttributes: new AddressData(customerId: 5, address1: '1 High St', country: 'GB'),
        paymentAttributes: new PaymentData(paymentType: PaymentType::CreditCard),
        customerNoteAttributes: new NoteData(text: 'Gift wrap'),
    ));

    expect($order)->toBeInstanceOf(Order::class);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && json_decode((string) $response->getPsrRequest()->getBody(), true) === ['order' => [
            'channel_id' => 1,
            'delivery_method_id' => 2,
            'line_items_attributes' => [
                ['sellable_id' => 3, 'price_per_unit' => '9.99', 'quantity' => 2, 'tax_rate' => 0.2],
                ['sellable_id' => 4, 'price_per_unit' => '1', 'quantity' => 1],
            ],
            'customer_id' => 5,
            'due_date' => '2026-01-31T09:30:00-05:00',
            'deliver_to_attributes' => ['customer_id' => 5, 'address1' => '1 High St', 'country' => 'GB'],
            'payment_attributes' => ['payment_type' => 'credit_card'],
            'customer_note_attributes' => ['text' => 'Gift wrap'],
        ]]);
});

it('updates an order, sending only the fields given', function () {
    $mock = new MockClient([UpdateOrder::class => MockResponse::make(['id' => 1051])]);

    $result = veeqo($mock)->orders()->update(1051, new UpdateOrderData(totalDiscounts: '0.50', deliverToAttributes: new AddressData(customerId: 5, zip: '90210')));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/1051'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"order":{"total_discounts":"0.50","deliver_to_attributes":{"customer_id":5,"zip":"90210"}}}');
});

it('sends an explicit null set through body()', function () {
    $mock = new MockClient([UpdateOrder::class => MockResponse::make(status: 204)]);
    $request = new UpdateOrder(1051, new UpdateOrderData(deliveryMethodId: 2));
    $request->body()->set(['order' => [...$request->body()->get('order'), 'due_date' => null]]);

    veeqo($mock)->send($request);

    $mock->assertSent(fn ($request, $response) => (string) $response->getPsrRequest()->getBody() === '{"order":{"delivery_method_id":2,"due_date":null}}');
});

it('cancels an order, sending its id beside the given fields', function () {
    $mock = new MockClient([CancelOrder::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->orders()->cancel(1051, new CancelOrderData(cancelReason: 'Out of stock'));
    veeqo($mock)->orders()->cancel(1052);

    expect($result)->toBeNull();
    expect(array_map(
        fn ($response) => [$response->getPsrRequest()->getMethod(), $response->getPendingRequest()->getUrl(), (string) $response->getPsrRequest()->getBody()],
        $mock->getRecordedResponses(),
    ))->toBe([
        ['PUT', 'https://api.veeqo.com/orders/1051/cancel', '{"id":1051,"cancel_reason":"Out of stock"}'],
        ['PUT', 'https://api.veeqo.com/orders/1052/cancel', '{"id":1052}'],
    ]);
});

it('adds a note to an order', function () {
    $mock = new MockClient([CreateNote::class => fixtureResponse('docs/create-a-new-order-note')]);

    $note = veeqo($mock)->notes()->create(323498, new CreateNoteData(text: 'Customer wants it very soon'));

    expect($note)->toBeInstanceOf(Note::class)
        ->and($note->id)->toBe(287170);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/323498/notes'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"note":{"text":"Customer wants it very soon"}}');
});

it('updates a line item', function () {
    $mock = new MockClient([UpdateLineItem::class => MockResponse::make(['id' => 7])]);

    $result = veeqo($mock)->lineItems()->update(7, new UpdateLineItemData(additionalOptions: 'Engrave: SJ'));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/line_items/7'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"additional_options":"Engrave: SJ"}');
});
