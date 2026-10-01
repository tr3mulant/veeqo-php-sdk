<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Email Purchase Order Reminders
 *
 * Queues a reminder email to the supplier for each id that resolves to one of the company’s ACTIVE
 * purchase orders. Ids that are not found, not the company’s, or not active are skipped SILENTLY,
 * and the response is 201 {} either way - so treat it as “requested”, never as “sent”. Email
 * is also suppressed entirely for some companies (multi-channel accounts, and sellers who have not
 * used the standalone app). The reminder attaches the reminder document as a PDF by default. Unlike
 * the other purchase order endpoints this one has no plan-feature gate, only the
 * manage_purchase_orders permission.
 */
class EmailPurchaseOrderReminders extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders/reminder";
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
