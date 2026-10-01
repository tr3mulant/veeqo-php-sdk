<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Purchase Orders
 *
 * 🚀 Paid plan only Lists purchase orders. Rows are the SAME shape as Get Purchase Order, already
 * carrying the nested supplier, destination warehouse, creator and the full line-item array - no
 * per-row follow-up read is needed. This endpoint is search-index backed and indexing is asynchronous,
 * so a just-created or just-updated purchase order can be absent or stale here. Confirm a write with
 * Get Purchase Order, which reads the database. Pagination totals are returned in the X-Total-Count ,
 * X-Total-Pages-Count , X-Page-Index and X-Per-Page response headers, not in the body. Do not send
 * pageable - it is not a supported parameter here and errors.
 */
class ListAllPurchaseOrders extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders";
	}


	/**
	 * @param null|int $pageSize Results per page. Default 25, silently capped at 100.
	 * @param null|int $page Page to show. Totals are returned in the X-Total-Count and X-Total-Pages-Count response headers.
	 * @param null|string $query Free-text search across supplier name and post code, purchase order number, reference number, and line-item variant title, SKU, UPC and supplier reference. Substring matching. Search-index backed, so a just-written purchase order can be missing here - read it back with Get Purchase Order instead.
	 * @param null|string $state Lifecycle state, or a received-status aggregate. An unrecognised value is a 400. There is no default, so drafts are included unless you filter. not_received , partially_received , fully_received , past_due and all are FILTER-ONLY values and never appear in a response state .
	 * @param null|int $supplierId Only purchase orders for this supplier.
	 * @param null|int $destinationWarehouseId Only purchase orders delivering to this warehouse.
	 * @param null|int $productVariantId Only ACTIVE purchase orders containing this variant (sellable) id.
	 * @param null|string $productVariantUpcCode Only purchase orders containing a line item with this UPC.
	 * @param null|string $referenceNumber Exact match on the purchase order reference number.
	 * @param null|string $createdafter Created on or after this date. A range where after > before is a 400.
	 * @param null|string $createdbefore Created on or before this date (inclusive of that whole day).
	 * @param null|string $createdAtMin Created at or after this timestamp.
	 * @param null|string $updatedAtMin Updated at or after this timestamp.
	 * @param null|int $sinceId Only purchase orders with an id greater than this.
	 * @param null|string $sortBy Field to sort on. Only supplier_name , created_at , expected_date , estimated_delivery_days and sort_by_received_percent are honoured; any other value is IGNORED silently and the results come back created_at descending, which is also the default when this is omitted.
	 * @param null|string $order Sort direction. Only used alongside a recognised sort_by .
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?string $query = null,
		protected ?string $state = null,
		protected ?int $supplierId = null,
		protected ?int $destinationWarehouseId = null,
		protected ?int $productVariantId = null,
		protected ?string $productVariantUpcCode = null,
		protected ?string $referenceNumber = null,
		protected ?string $createdafter = null,
		protected ?string $createdbefore = null,
		protected ?string $createdAtMin = null,
		protected ?string $updatedAtMin = null,
		protected ?int $sinceId = null,
		protected ?string $sortBy = null,
		protected ?string $order = null,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter([
			'page_size' => $this->pageSize,
			'page' => $this->page,
			'query' => $this->query,
			'state' => $this->state,
			'supplier_id' => $this->supplierId,
			'destination_warehouse_id' => $this->destinationWarehouseId,
			'product_variant_id' => $this->productVariantId,
			'product_variant_upc_code' => $this->productVariantUpcCode,
			'reference_number' => $this->referenceNumber,
			'created[after]' => $this->createdafter,
			'created[before]' => $this->createdbefore,
			'created_at_min' => $this->createdAtMin,
			'updated_at_min' => $this->updatedAtMin,
			'since_id' => $this->sinceId,
			'sort_by' => $this->sortBy,
			'order' => $this->order,
		]);
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
