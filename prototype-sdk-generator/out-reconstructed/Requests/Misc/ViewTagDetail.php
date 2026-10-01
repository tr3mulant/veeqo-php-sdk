<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View an Tag Detail
 */
class ViewTagDetail extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/tags/{$this->tagId}";
	}


	/**
	 * @param int $tagId ID of the Tag
	 */
	public function __construct(
		protected int $tagId,
	) {
	}
}
