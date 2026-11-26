<?php

use Saloon\Http\Faking\MockClient;
use TremulantTech\Veeqo\Requests\Rates\GetRate;
use TremulantTech\Veeqo\Responses\Rate;

it('gets an allocation\'s rates as a typed Rate, sending its filters on the wire', function () {
    $mock = new MockClient([GetRate::class => fixtureResponse('docs/retrieve-shipping-rates')]);

    $rate = veeqo($mock)->rates()->get(allocationId: 55443322, formatWithUnavailableQuotes: true, shippingConfigurationIds: [123456, 7]);

    $quote = $rate->available[0] ?? null;
    expect($rate)->toBeInstanceOf(Rate::class)
        ->and($quote?->name)->toBe('amazon_shipping_v2-2295c3aa-af8c-48a0-9f19-cd84d53cbd36')
        ->and($quote?->totalNetCharge)->toBe('11.80')
        ->and($quote?->cutoff?->format(DATE_ATOM))->toBe('2025-06-10T22:00:00+01:00')
        ->and($quote?->charges[1]->price ?? null)->toBe('$5.90')
        ->and($quote?->shippingServiceOptions[0]->values[1]->price ?? null)->toBe('5.9')
        ->and($quote?->shippingServiceOptions[1]->validation?->max)->toBe(50000.0)
        ->and($quote?->documentSpecification?->docSpec?->printOptions[0]->supportedDocumentDetails[0]->isMandatory ?? null)->toBeTrue()
        ->and($rate->unavailable[0]->unavailableReasons[0]->message ?? null)->toBe('It is not an eligible ship method for this order.')
        ->and($rate->linkedAccounts[0]->shippingConfigurationId ?? null)->toBe(123456);
    $mock->assertSent(fn ($request, $response) => (string) $response->getPsrRequest()->getUri() === 'https://api.veeqo.com/shipping/rates/55443322?from_allocation_package=true&format_with_unavailable_quotes=true&shipping_configuration_ids%5B%5D=123456&shipping_configuration_ids%5B%5D=7'
        && $response->getPsrRequest()->getMethod() === 'GET');
});

it('always rates from the allocation package and leaves unset filters off the wire', function () {
    $mock = new MockClient([GetRate::class => fixtureResponse('docs/retrieve-shipping-rates')]);

    veeqo($mock)->rates()->get(allocationId: 1);

    $mock->assertSent(fn ($request, $response) => $response->getPsrRequest()->getUri()->getQuery() === 'from_allocation_package=true');
});
