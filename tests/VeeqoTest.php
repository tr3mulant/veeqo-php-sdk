<?php

use IronGate\Veeqo\Requests\Orders\GetOrder;
use IronGate\Veeqo\Requests\Orders\ListOrders;
use IronGate\Veeqo\Veeqo;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

function veeqo(MockClient $mock): Veeqo
{
    $veeqo = new Veeqo('test-key');
    $veeqo->retryInterval = 0;

    return $veeqo->withMockClient($mock);
}

it('sends the api key and fetches an order', function () {
    $mock = new MockClient([GetOrder::class => MockResponse::make(['id' => 42])]);

    $order = veeqo($mock)->send(new GetOrder(42))->json();

    expect($order['id'])->toBe(42);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->headers()->get('x-api-key') === 'test-key'
        && $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/orders/42');
});

it('paginates with page and page_size until a short page', function () {
    $pages = [];
    $mock = new MockClient([ListOrders::class => function (PendingRequest $request) use (&$pages) {
        $pages[] = $request->query()->all();
        $count = $request->query()->get('page') === 1 ? 100 : 3;

        return MockResponse::make(array_fill(0, $count, ['id' => 1]));
    }]);

    $items = veeqo($mock)->paginate(new ListOrders(['status' => 'shipped']))->items();

    expect(iterator_count($items))->toBe(103)
        ->and($pages)->toBe([
            ['status' => 'shipped', 'page' => 1, 'page_size' => 100],
            ['status' => 'shipped', 'page' => 2, 'page_size' => 100],
        ]);
});

it('retries on 429 but not on other errors', function () {
    $mock = new MockClient([
        MockResponse::make([], 429),
        MockResponse::make(['id' => 1]),
    ]);
    expect(veeqo($mock)->send(new GetOrder(1))->json('id'))->toBe(1);

    $mock = new MockClient([MockResponse::make([], 404), MockResponse::make(['id' => 1])]);
    expect(fn () => veeqo($mock)->send(new GetOrder(1)))->toThrow(Saloon\Exceptions\Request\Statuses\NotFoundException::class);
});
