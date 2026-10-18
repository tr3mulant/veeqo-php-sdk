<?php

use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Exceptions\Forbidden;
use TremulantTech\Veeqo\Exceptions\NotFound;
use TremulantTech\Veeqo\Exceptions\RateLimited;
use TremulantTech\Veeqo\Exceptions\ServerError;
use TremulantTech\Veeqo\Exceptions\Unauthorized;
use TremulantTech\Veeqo\Exceptions\ValidationFailed;
use TremulantTech\Veeqo\Exceptions\VeeqoException;
use TremulantTech\Veeqo\Exceptions\VeeqoRequestException;
use TremulantTech\Veeqo\Requests\Orders\GetOrder;

function caught(MockResponse $response): VeeqoRequestException
{
    try {
        veeqo(new MockClient([GetOrder::class => $response]))->send(new GetOrder(1));
    } catch (VeeqoRequestException $e) {
        return $e;
    }

    throw new LogicException('Expected a VeeqoRequestException');
}

it('throws an SDK exception per status', function (MockResponse $response, string $class) {
    $e = caught($response);

    expect($e)->toBeInstanceOf($class)
        ->toBeInstanceOf(VeeqoException::class)
        ->toBeInstanceOf(RequestException::class);
})->with([
    '400' => [fn () => captured('errors.400.orders.bad_page_size'), ValidationFailed::class],
    '422' => [fn () => MockResponse::make(['error_messages' => ['Sku is taken']], 422), ValidationFailed::class],
    '401' => [fn () => captured('errors.401'), Unauthorized::class],
    '403' => [fn () => captured('errors.403.suppliers.list'), Forbidden::class],
    '404' => [fn () => captured('errors.404.order'), NotFound::class],
    '429' => [fn () => captured('errors.429'), RateLimited::class],
    '500' => [fn () => MockResponse::make('', 500), ServerError::class],
    '503' => [fn () => MockResponse::make('<html>down</html>', 503), ServerError::class],
]);

it('throws the base exception for a status without a subclass', function () {
    expect(get_class(caught(MockResponse::make([], 409))))->toBe(VeeqoRequestException::class);
});

it('does not extend Saloon status exceptions', function () {
    expect(caught(captured('errors.404.order')))->not->toBeInstanceOf(Saloon\Exceptions\Request\Statuses\NotFoundException::class);
});

it('flattens every Veeqo error body shape', function (MockResponse $response, array $messages) {
    expect(caught($response)->errorMessages())->toBe($messages);
})->with([
    'list' => [fn () => captured('errors.400.orders.bad_page_size'), ['Infinity']],
    'string' => [fn () => captured('errors.400.shipping.rates'), ['Order must have a destination address.']],
    'status/error, string status' => [fn () => captured('errors.404.product'), ['Not Found']],
    'status/error, int status' => [fn () => captured('errors.404.route'), ['Not Found']],
    '422 list' => [fn () => MockResponse::make(['error_messages' => ['Sku is taken', 'Title is blank']], 422), ['Sku is taken', 'Title is blank']],
    'json-encoded upstream error stays a string' => [fn () => MockResponse::make(['error_messages' => ['{"code":"bad"}']], 422), ['{"code":"bad"}']],
    'empty 429' => [fn () => captured('errors.429'), []],
    'non-json 5xx' => [fn () => MockResponse::make('<html>down</html>', 503), []],
]);

it('leaves connection failures as Saloon fatal exceptions', function () {
    $mock = new MockClient([GetOrder::class => connectionFailure()]);

    expect(fn () => veeqo($mock)->send(new GetOrder(1)))->toThrow(FatalRequestException::class);
});
