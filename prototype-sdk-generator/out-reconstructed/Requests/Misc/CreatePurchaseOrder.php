<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Purchase Order
 *
 * 🚀 Paid plan only Creates a purchase order. The purchase_order envelope key is required, and
 * attributes outside the documented set are dropped SILENTLY rather than rejected. product_variant_id
 * on a line item is the SELLABLE id (the same id the sellables API returns) - no translation is
 * needed. number is derived as <company purchase order prefix>-<count + 1> when omitted; a supplied
 * value is accepted with no uniqueness check, so omit it. estimated_delivery_days is recalculated from
 * expected_date on any save that changes that date, so send the date and leave the days alone. Totals
 * are computed on read rather than stored. received_quantity on a line item is IGNORED on create -
 * receiving only happens on update.
 */
class CreatePurchaseOrder extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders";
	}


	/**
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
