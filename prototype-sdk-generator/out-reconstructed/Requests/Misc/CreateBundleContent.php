<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Bundle Content
 *
 * Add a new product variant to an existing bundle.
 */
class CreateBundleContent extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/kits/{$this->kitId}/kit_contents";
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
