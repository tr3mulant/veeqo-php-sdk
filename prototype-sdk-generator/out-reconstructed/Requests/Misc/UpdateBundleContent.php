<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Bundle Content
 *
 * Update the product variant or quantity of a particular item in a bundle.
 */
class UpdateBundleContent extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/kits/{$this->kitId}/kit_contents/{$this->kitContentId}";
	}


	/**
	 * @param float|int $kitId ID of the bundle.
	 * @param float|int $kitContentId The ID of the kit content. This is different from the product variant ID.
	 */
	public function __construct(
		protected float|int $kitId,
		protected float|int $kitContentId,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['kit_id' => $this->kitId, 'kit_content_id' => $this->kitContentId]);
	}
}
