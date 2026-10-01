<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Purchase Order Line Item
 *
 * Updates ONE line item in place. Accepts only received_quantity , display_position , quantity , cost
 * , tax_rate , supplier_reference_number and supplier_title , wrapped in a
 * purchase_order_product_variant envelope. WARNING: received_quantity here adjusts warehouse stock but
 * does NOT update the line item’s stored received count, which leaves the two out of step. To record
 * a receipt, send nested purchase_order_product_variants_attributes on Update Purchase Order instead -
 * that path applies the delta to both.
 */
class UpdatePurchaseOrderLineItem extends Request
{
	protected Method $method = Method::PUT;


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
