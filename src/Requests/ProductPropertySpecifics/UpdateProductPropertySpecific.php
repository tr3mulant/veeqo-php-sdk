<?php

namespace TremulantTech\Veeqo\Requests\ProductPropertySpecifics;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\ProductPropertySpecifics\UpdateProductPropertySpecificData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-property-detail/
 */
class UpdateProductPropertySpecific extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $productId, protected int $id, protected UpdateProductPropertySpecificData $data) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->productId}/product_property_specifics/{$this->id}";
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
