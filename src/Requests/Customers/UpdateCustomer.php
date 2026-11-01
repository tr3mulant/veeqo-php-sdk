<?php

namespace TremulantTech\Veeqo\Requests\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Customers\UpdateCustomerData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-customer-detail/
 */
class UpdateCustomer extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $id, protected UpdateCustomerData $data) {}

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->id}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['customer' => Veeqo::normalize($this->data)];
    }
}
