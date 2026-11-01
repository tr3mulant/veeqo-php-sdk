<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use TremulantTech\Veeqo\Data\Customers\AddressData;
use TremulantTech\Veeqo\Data\Customers\CreateCustomerData;
use TremulantTech\Veeqo\Data\Customers\UpdateCustomerData;
use TremulantTech\Veeqo\Enums\CustomerType;
use TremulantTech\Veeqo\Requests\Customers\CreateCustomer;
use TremulantTech\Veeqo\Requests\Customers\GetCustomer;
use TremulantTech\Veeqo\Requests\Customers\ListCustomers;
use TremulantTech\Veeqo\Requests\Customers\UpdateCustomer;
use TremulantTech\Veeqo\Responses\Customer;

it('gets a customer as a typed Customer', function () {
    $mock = new MockClient([GetCustomer::class => fixtureResponse('responses/customers.show')]);

    $customer = veeqo($mock)->customers()->get(1013);

    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->id)->toBe(1013)
        ->and($customer->customerType)->toBe(CustomerType::Retail)
        ->and($customer->shippingAddresses[0]->id)->toBe(1015);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/customers/1013'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('lists customers lazily across pages, sending filters as Veeqo expects', function () {
    $queries = [];
    $mock = new MockClient([ListCustomers::class => function (PendingRequest $request) use (&$queries) {
        $queries[] = $request->createPsrRequest()->getUri()->getQuery();
        $customers = json_decode(fixtureResponse('responses/customers.list')->body(), true);

        return MockResponse::make($request->query()->get('page') === 1 ? array_merge(...array_fill(0, 10, $customers)) : $customers);
    }]);

    $customers = veeqo($mock)->customers()->list(query: 'phil', customerType: CustomerType::Business);

    expect($queries)->toBe([]);
    $customers = iterator_to_array($customers, false);

    expect($customers)->toHaveCount(110)->each->toBeInstanceOf(Customer::class)
        ->and($queries)->toBe([
            'query=phil&customer_type=business&page=1&page_size=100',
            'query=phil&customer_type=business&page=2&page_size=100',
        ]);
});

it('creates a customer, sending the envelope, wire keys and enum values and dropping nulls', function () {
    $mock = new MockClient([CreateCustomer::class => fixtureResponse('docs/create-a-customer')]);

    $customer = veeqo($mock)->customers()->create(new CreateCustomerData(
        email: 'phil@example.com',
        customerType: CustomerType::Retail,
        mobile: '7123456789',
        billingAddressAttributes: new AddressData(firstName: 'Phil', city: 'Swansea', country: 'GB'),
    ));

    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->id)->toBe(123)
        ->and($customer->mobile)->toBe('7123456789');
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/customers'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"customer":{"email":"phil@example.com","customer_type":"retail","mobile":"7123456789","billing_address_attributes":{"first_name":"Phil","city":"Swansea","country":"GB"}}}');
});

it('updates a customer, sending the envelope and dropping nulls', function () {
    $mock = new MockClient([UpdateCustomer::class => fixtureResponse('docs/update-customer-detail')]);

    $customer = veeqo($mock)->customers()->update(123, new UpdateCustomerData(
        email: 'phil@example.com',
        customerType: CustomerType::Business,
        notes: 'Wholesale now',
        billingAddressAttributes: new AddressData(zip: 'SA1 1NW'),
    ));

    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->id)->toBe(123);
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/customers/123'
        && $response->getPsrRequest()->getMethod() === 'PUT'
        && (string) $response->getPsrRequest()->getBody() === '{"customer":{"email":"phil@example.com","customer_type":"business","notes":"Wholesale now","billing_address_attributes":{"zip":"SA1 1NW"}}}');
});
