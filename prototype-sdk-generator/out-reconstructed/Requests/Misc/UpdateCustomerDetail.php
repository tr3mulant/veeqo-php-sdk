<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Customer Detail
 */
class UpdateCustomerDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/customers/{$this->id}";
	}


	/**
	 * @param int $id ID of the customer.
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
