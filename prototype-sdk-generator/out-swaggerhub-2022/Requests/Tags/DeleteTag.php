<?php

namespace IronGate\Veeqo\Requests\Tags;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Tag
 */
class DeleteTag extends Request
{
	protected Method $method = Method::DELETE;


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
