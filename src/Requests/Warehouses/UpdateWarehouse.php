<?php

namespace TremulantTech\Veeqo\Requests\Warehouses;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\Warehouses\UpdateWarehouseData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/update-warehouse-detail/
 */
class UpdateWarehouse extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(protected int $id, protected UpdateWarehouseData $data) {}

    public function resolveEndpoint(): string
    {
        return "/warehouses/{$this->id}";
    }

    /**
     * Unenveloped, as the spec's schema documents (its example alone wraps the body in `warehouse`).
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
