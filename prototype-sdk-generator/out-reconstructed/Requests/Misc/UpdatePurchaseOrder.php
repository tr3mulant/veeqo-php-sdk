<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Purchase Order
 *
 * 🚀 Paid plan only Updates a purchase order. Also accepts PATCH . state is a plain attribute
 * assignment with no inclusion check, so only ever send draft , active or completed - any other value
 * persists and hides the purchase order from every state filter. Moving to active has server-side
 * effects: it creates stock entries for every variant at the destination warehouse and is what enables
 * the supplier emails. received_quantity on a line item is a DELTA, not a target: stored received
 * becomes previous + received_quantity , warehouse physical stock is adjusted by the delta, the
 * variant cost price is recalculated and received_at is stamped. It is NOT idempotent and there is no
 * reverse, so send it once and never retry it automatically. For a purchase order in a currency other
 * than the company’s, set currency_rate explicitly - it defaults to 0 and the cost-price
 * recalculation multiplies by it. Line items are edited by id and added by omitting the id. There is
 * no _destroy : use Delete Purchase Order Line Item. Add only ONE new line item per request - a second
 * line for a variant already on the purchase order is dropped silently.
 */
class UpdatePurchaseOrder extends Request
{
	protected Method $method = Method::PUT;


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
