<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Orders\CreateNewOrder;
use IronGate\Veeqo\Requests\Orders\CreateNewOrderNote;
use IronGate\Veeqo\Requests\Orders\ListAllOrders;
use IronGate\Veeqo\Requests\Orders\UpdateOrderDetail;
use IronGate\Veeqo\Requests\Orders\ViewOrderDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Orders extends BaseResource
{
	/**
	 * @param int $sinceId Restrict results to after specified ID
	 * @param string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $query Free text search
	 * @param string $status Order Status
	 * @param string $tags Restrict results to orders with a tag of the name provided
	 * @param int $allocatedAt Restrict results to orders allocated at a specific warehouse
	 */
	public function listAllOrders(
		?int $sinceId = null,
		?string $createdAtMin = null,
		?string $updatedAtMin = null,
		?int $pageSize = null,
		?int $page = null,
		?string $query = null,
		?string $status = null,
		?string $tags = null,
		?int $allocatedAt = null,
	): Response
	{
		return $this->connector->send(new ListAllOrders($sinceId, $createdAtMin, $updatedAtMin, $pageSize, $page, $query, $status, $tags, $allocatedAt));
	}


	public function createNewOrder(): Response
	{
		return $this->connector->send(new CreateNewOrder());
	}


	/**
	 * @param int $orderId ID of the Order
	 */
	public function viewOrderDetail(int $orderId): Response
	{
		return $this->connector->send(new ViewOrderDetail($orderId));
	}


	/**
	 * @param int $orderId ID of the Order
	 */
	public function updateOrderDetail(int $orderId): Response
	{
		return $this->connector->send(new UpdateOrderDetail($orderId));
	}


	/**
	 * @param int $orderId Order ID
	 */
	public function createNewOrderNote(int $orderId): Response
	{
		return $this->connector->send(new CreateNewOrderNote($orderId));
	}
}
