<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieve Bundle Detail
 *
 * Get info about a bundle by its ID.
 */
class RetrieveBundleDetail extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/kits/{$this->kitId}";
	}


	/**
	 * @param float|int $kitId ID of the bundle.
	 */
	public function __construct(
		protected float|int $kitId,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['kit_id' => $this->kitId]);
	}
}
