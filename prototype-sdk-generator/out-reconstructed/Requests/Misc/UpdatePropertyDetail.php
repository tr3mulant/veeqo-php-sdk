<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Property Detail
 */
class UpdatePropertyDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/products/{$this->productId}/product_property_specifics/{$this->propertyId}";
	}


	/**
	 * @param int $productId ID of the Product
	 * @param int $propertyId ID of the Property
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected int $productId,
		protected int $propertyId,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
