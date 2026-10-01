<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Line Item Notes
 *
 * Add notes or additional options to a line item.
 */
class UpdateLineItemNotes extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/line_items/{$this->id}";
	}


	/**
	 * @param int $id ID of the line item to update.
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
