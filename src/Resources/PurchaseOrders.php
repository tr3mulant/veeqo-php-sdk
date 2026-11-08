<?php

namespace TremulantTech\Veeqo\Resources;

use DateTimeInterface;
use Generator;
use Psr\Http\Message\StreamInterface;
use Saloon\Http\Request;
use TremulantTech\Veeqo\Data\PurchaseOrders\CreatePurchaseOrderData;
use TremulantTech\Veeqo\Data\PurchaseOrders\ReminderPurchaseOrderData;
use TremulantTech\Veeqo\Data\PurchaseOrders\UpdatePurchaseOrderData;
use TremulantTech\Veeqo\Enums\PurchaseOrderOrder;
use TremulantTech\Veeqo\Enums\PurchaseOrderState;
use TremulantTech\Veeqo\Exceptions\UnexpectedResponse;
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

        return $this->veeqo->hydratePages(PurchaseOrder::class, $request);
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

    /**
     * @throws UnexpectedResponse on Veeqo's documented 302, which is not followed: its Location is on the response.
     */
    public function downloadCsv(int $id): StreamInterface
    {
        return $this->download(new DownloadPurchaseOrderCsv($id));
    }

    /**
     * @throws UnexpectedResponse on Veeqo's documented 302, which is not followed: its Location is on the response.
     */
    public function downloadPdf(int $id): StreamInterface
    {
        return $this->download(new DownloadPurchaseOrderPdf($id));
    }

    private function download(Request $request): StreamInterface
    {
        $response = $this->veeqo->send($request);
        if ($response->status() !== 200) {
            throw new UnexpectedResponse($response, ["Expected a 200 download, got {$response->status()}"]);
        }

        return $response->stream();
    }

    /**
     * Veeqo answers 201 `{}` whether or not any email is sent, so nothing is returned (ADR 0004).
     */
    public function reminder(ReminderPurchaseOrderData $data): void
    {
        $this->veeqo->send(new ReminderPurchaseOrder($data));
    }
}
