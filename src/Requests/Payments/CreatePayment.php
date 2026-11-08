<?php

namespace TremulantTech\Veeqo\Requests\Payments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Payments\CreatePaymentData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-payment/
 */
class CreatePayment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreatePaymentData $data) {}

    public function resolveEndpoint(): string
    {
        return '/payments';
    }

    /**
     * Unenveloped, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
