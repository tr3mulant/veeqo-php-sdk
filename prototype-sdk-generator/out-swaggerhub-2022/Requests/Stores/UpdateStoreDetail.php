<?php

namespace IronGate\Veeqo\Requests\Stores;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Store Detail
 */
class UpdateStoreDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/channels/{$this->id}";
	}


	/**
	 * @param int $id
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
