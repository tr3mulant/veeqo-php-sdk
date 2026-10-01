<?php

namespace IronGate\Veeqo\Requests\Customers;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Customer Detail
 */
class ViewCustomerDetail extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/customers/{$this->id}";
	}


	/**
	 * @param int $id
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
