<?php

namespace IronGate\Veeqo\Requests\Stores;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Store Detail
 */
class ViewStoreDetail extends Request
{
	protected Method $method = Method::GET;


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
