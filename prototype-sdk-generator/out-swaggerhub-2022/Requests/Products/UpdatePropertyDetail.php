<?php

namespace IronGate\Veeqo\Requests\Products;

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
	 */
	public function __construct(
		protected int $productId,
		protected int $propertyId,
	) {
	}
}
