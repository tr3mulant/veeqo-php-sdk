<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Allocations\CreateNewAllocation;
use IronGate\Veeqo\Requests\Allocations\DeleteAllocation;
use IronGate\Veeqo\Requests\Allocations\UpdateAllocationDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Allocations extends BaseResource
{
	/**
	 * @param int $orderId Order ID
	 */
	public function createNewAllocation(int $orderId): Response
	{
		return $this->connector->send(new CreateNewAllocation($orderId));
	}


	/**
	 * @param int $orderId ID of the Order
	 * @param int $allocationId ID of the Allocation
	 */
	public function updateAllocationDetail(int $orderId, int $allocationId): Response
	{
		return $this->connector->send(new UpdateAllocationDetail($orderId, $allocationId));
	}


	/**
	 * @param int $orderId ID of the Order
	 * @param int $allocationId ID of the Allocation
	 */
	public function deleteAllocation(int $orderId, int $allocationId): Response
	{
		return $this->connector->send(new DeleteAllocation($orderId, $allocationId));
	}
}
