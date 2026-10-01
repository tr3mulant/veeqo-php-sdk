<?php

namespace IronGate\Veeqo\Requests\Allocations;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Allocation
 */
class DeleteAllocation extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/orders/{$this->orderId}/allocations/{$this->allocationId}";
	}


	/**
	 * @param int $orderId ID of the Order
	 * @param int $allocationId ID of the Allocation
	 */
	public function __construct(
		protected int $orderId,
		protected int $allocationId,
	) {
	}
}
