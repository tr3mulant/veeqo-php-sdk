<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Product Detail
 */
class UpdateProductDetail extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/products/{$this->productId}";
	}


	/**
	 * @param int $productId ID of the Product
	 */
	public function __construct(
		protected int $productId,
	) {
	}
}
