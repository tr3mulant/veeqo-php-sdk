<?php

namespace TremulantTech\Veeqo\Requests\Suppliers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Suppliers\CreateSupplierData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/create-a-new-supplier/
 */
class CreateSupplier extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateSupplierData $data) {}

    public function resolveEndpoint(): string
    {
        return '/suppliers';
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
