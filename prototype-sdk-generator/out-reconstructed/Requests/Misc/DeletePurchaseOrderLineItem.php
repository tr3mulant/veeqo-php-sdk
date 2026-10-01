<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Purchase Order Line Item
 *
 * Removes one line item. This is the ONLY way to remove a line item - nested line-item attributes on
 * Update Purchase Order have no _destroy support.
 */
class DeletePurchaseOrderLineItem extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders/{$this->purchaseOrderId}/purchase_order_product_variants/{$this->id}";
	}


	/**
	 * @param int $purchaseOrderId
	 * @param int $id Line item (purchase_order_product_variant) id.
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected int $purchaseOrderId,
		protected int $id,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
