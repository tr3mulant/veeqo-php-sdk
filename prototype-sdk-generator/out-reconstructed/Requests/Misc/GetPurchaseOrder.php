<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Purchase Order
 *
 * 🚀 Paid plan only Reads one purchase order as JSON. The response is identical in shape to a List
 * row, including the nested supplier , destination_warehouse , created_by and the full
 * purchase_order_product_variants array, so a list does not need a follow-up read per row. There is no
 * line_items field on a purchase order. Unlike List, this reads the database rather than the search
 * index, so it is the way to confirm a write. .json may be appended to the path explicitly; the
 * unsuffixed path returns the same JSON. The rendered document formats are separate operations:
 * Download Purchase Order PDF and Download Purchase Order CSV. There is NO printing job for a purchase
 * order - POST /api/v2/printing_jobs rejects entity_type: purchase_order , and no printing-job
 * template renders one. Decimal fields ( cost , tax_rate , subtotal , total_tax , total_excluding_tax
 * , total_including_tax , shipping_and_handling , currency_rate ) are serialized as JSON strings.
 */
class GetPurchaseOrder extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders/{$this->id}";
	}


	/**
	 * @param int $id Purchase order ID.
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected int $id,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
