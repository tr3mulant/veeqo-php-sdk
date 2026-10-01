<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Update Allocation Package
 *
 * Update an allocation’s package. Useful if you need to specify package dimensions before making
 * requests for shipping rates.
 */
class UpdateAllocationPackage extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/allocations/{$this->allocationId}/allocation_package";
	}


	/**
	 * @param int $allocationId ID of the Allocation
	 */
	public function __construct(
		protected int $allocationId,
	) {
	}
}
