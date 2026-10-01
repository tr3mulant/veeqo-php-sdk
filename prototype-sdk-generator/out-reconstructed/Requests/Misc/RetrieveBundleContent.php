<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieve Bundle Content
 *
 * Get details about a specific content within a bundle.
 */
class RetrieveBundleContent extends Request
{
	protected Method $method = Method::GET;


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
