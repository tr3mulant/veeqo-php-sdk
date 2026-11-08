<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrders;

use DateTimeInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use TremulantTech\Veeqo\Enums\PurchaseOrderOrder;
use TremulantTech\Veeqo\Enums\PurchaseOrderState;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/list-all-purchase-orders/
 */
class ListPurchaseOrders extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /** @var array<string, int|string> */
    private readonly array $filters;

    /**
     * @param  DateTimeInterface|null  $createdBefore  The whole UTC day of this date is included.
     * @param  string|null  $sortBy  Only supplier_name, created_at, expected_date, estimated_delivery_days and sort_by_received_percent are honoured; Veeqo ignores any other value.
     */
    public function __construct(
        ?string $query = null,
        ?PurchaseOrderState $state = null,
        ?int $supplierId = null,
        ?int $destinationWarehouseId = null,
        ?int $productVariantId = null,
        ?string $productVariantUpcCode = null,
        ?string $referenceNumber = null,
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $createdBefore = null,
        ?DateTimeInterface $createdAtMin = null,
        ?DateTimeInterface $updatedAtMin = null,
        ?int $sinceId = null,
        ?string $sortBy = null,
        ?PurchaseOrderOrder $order = null,
    ) {
        $this->filters = array_filter([
            'query' => $query,
            'state' => $state?->value,
            'supplier_id' => $supplierId,
            'destination_warehouse_id' => $destinationWarehouseId,
            'product_variant_id' => $productVariantId,
            'product_variant_upc_code' => $productVariantUpcCode,
            'reference_number' => $referenceNumber,
            'created[after]' => Veeqo::queryDate($createdAfter),
            'created[before]' => Veeqo::queryDate($createdBefore),
            'created_at_min' => Veeqo::queryDate($createdAtMin),
            'updated_at_min' => Veeqo::queryDate($updatedAtMin),
            'since_id' => $sinceId,
            'sort_by' => $sortBy,
            'order' => $order?->value,
        ], fn (mixed $value) => $value !== null);
    }

    public function resolveEndpoint(): string
    {
        return '/purchase_orders';
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
