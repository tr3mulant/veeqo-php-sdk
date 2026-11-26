<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use TremulantTech\Veeqo\Data\Payments\CreatePaymentData;
use TremulantTech\Veeqo\Data\Payments\PaymentData;
use TremulantTech\Veeqo\Enums\PaymentType;
use TremulantTech\Veeqo\Requests\Payments\CreatePayment;

it('creates a payment, sending a flat body without the unset reference number', function () {
    $mock = new MockClient([CreatePayment::class => MockResponse::make(status: 204)]);

    $result = veeqo($mock)->payments()->create(new CreatePaymentData(
        paymentAttributes: new PaymentData(paymentType: PaymentType::CreditCard, orderId: 123456789),
        amount: '10.99',
    ));

    expect($result)->toBeNull();
    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getUrl() === 'https://api.veeqo.com/payments'
        && $response->getPsrRequest()->getMethod() === 'POST'
        && (string) $response->getPsrRequest()->getBody() === '{"payment_attributes":{"payment_type":"credit_card","order_id":123456789},"amount":"10.99"}');
});
