<?php

namespace TremulantTech\Veeqo\Resources;

use DateTimeInterface;
use Generator;
use LogicException;
use Psr\Http\Message\StreamInterface;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Data\PurchaseOrders\CreatePurchaseOrderData;
use TremulantTech\Veeqo\Data\PurchaseOrders\ReminderPurchaseOrderData;
use TremulantTech\Veeqo\Data\PurchaseOrders\UpdatePurchaseOrderData;
use TremulantTech\Veeqo\Enums\PurchaseOrderOrder;
use TremulantTech\Veeqo\Enums\PurchaseOrderState;
use TremulantTech\Veeqo\Requests\PurchaseOrders\CreatePurchaseOrder;
use TremulantTech\Veeqo\Requests\PurchaseOrders\DownloadPurchaseOrderCsv;
use TremulantTech\Veeqo\Requests\PurchaseOrders\DownloadPurchaseOrderPdf;
use TremulantTech\Veeqo\Requests\PurchaseOrders\GetPurchaseOrder;
use TremulantTech\Veeqo\Requests\PurchaseOrders\ListPurchaseOrders;
use TremulantTech\Veeqo\Requests\PurchaseOrders\ReminderPurchaseOrder;
use TremulantTech\Veeqo\Requests\PurchaseOrders\UpdatePurchaseOrder;
use TremulantTech\Veeqo\Responses\PurchaseOrder;
use TremulantTech\Veeqo\Veeqo;

class PurchaseOrders
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Search-index backed, so a just-written purchase order can be missing or stale; confirm a write with get().
     *
     * @param  DateTimeInterface|null  $createdBefore  The whole UTC day of this date is included.
     * @param  string|null  $sortBy  Only supplier_name, created_at, expected_date, estimated_delivery_days and sort_by_received_percent are honoured; Veeqo ignores any other value.
     * @return Generator<int, PurchaseOrder>
     */
    public function list(
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
    ): Generator {
        $request = new ListPurchaseOrders($query, $state, $supplierId, $destinationWarehouseId, $productVariantId, $productVariantUpcCode, $referenceNumber, $createdAfter, $createdBefore, $createdAtMin, $updatedAtMin, $sinceId, $sortBy, $order);

        foreach ($this->veeqo->paginate($request) as $page) {
            if (! $page instanceof Response) {
                throw new LogicException('Expected a synchronous page');
            }
            foreach ($this->veeqo->hydrateList(PurchaseOrder::class, $page) as $purchaseOrder) {
                yield $purchaseOrder;
            }
        }
    }

    public function get(int $id): PurchaseOrder
    {
        return $this->veeqo->hydrate(PurchaseOrder::class, $this->veeqo->send(new GetPurchaseOrder($id)));
    }

    public function create(CreatePurchaseOrderData $data): PurchaseOrder
    {
        return $this->veeqo->hydrate(PurchaseOrder::class, $this->veeqo->send(new CreatePurchaseOrder($data)));
    }

    /**
     * Not idempotent when a line item carries `receivedQuantity`, so the connector never resends it on a 5xx (ADR 0001).
     */
    public function update(int $id, UpdatePurchaseOrderData $data): PurchaseOrder
    {
        return $this->veeqo->hydrate(PurchaseOrder::class, $this->veeqo->send(new UpdatePurchaseOrder($id, $data)));
    }

    public function downloadCsv(int $id): StreamInterface
    {
        return $this->veeqo->send(new DownloadPurchaseOrderCsv($id))->stream();
    }

    public function downloadPdf(int $id): StreamInterface
    {
        return $this->veeqo->send(new DownloadPurchaseOrderPdf($id))->stream();
    }

    /**
     * Veeqo answers 201 `{}` whether or not any email is sent, so nothing is returned (ADR 0004).
     */
    public function reminder(ReminderPurchaseOrderData $data): void
    {
        $this->veeqo->send(new ReminderPurchaseOrder($data));
    }
}
