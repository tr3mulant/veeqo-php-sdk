<?php

namespace TremulantTech\Veeqo\Requests\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Customers\CreateCustomerData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-customer/
 */
class CreateCustomer extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateCustomerData $data) {}

    public function resolveEndpoint(): string
    {
        return '/customers';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['customer' => Veeqo::normalize($this->data)];
    }
}
