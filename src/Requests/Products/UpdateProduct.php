<?php

namespace TremulantTech\Veeqo\Requests\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Products\UpdateProductData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-product-detail/
 */
class UpdateProduct extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $id, protected UpdateProductData $data) {}

    public function resolveEndpoint(): string
    {
        return "/products/{$this->id}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['product' => Veeqo::normalize($this->data)];
    }
}
