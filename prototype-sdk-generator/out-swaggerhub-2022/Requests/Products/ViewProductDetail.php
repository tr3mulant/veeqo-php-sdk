<?php

namespace IronGate\Veeqo\Requests\Products;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Product Detail
 */
class ViewProductDetail extends Request
{
	protected Method $method = Method::GET;


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
