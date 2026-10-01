<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Warehouses\CreateWarehouse;
use IronGate\Veeqo\Requests\Warehouses\DeleteWarehouse;
use IronGate\Veeqo\Requests\Warehouses\ListAllWarehouses;
use IronGate\Veeqo\Requests\Warehouses\UpdateWarehouseDetail;
use IronGate\Veeqo\Requests\Warehouses\ViewWarehouseDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Warehouses extends BaseResource
{
	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 */
	public function listAllWarehouses(?int $pageSize = null, ?int $page = null): Response
	{
		return $this->connector->send(new ListAllWarehouses($pageSize, $page));
	}


	public function createWarehouse(): Response
	{
		return $this->connector->send(new CreateWarehouse());
	}


	/**
	 * @param int $id ID of the Warehouse
	 */
	public function viewWarehouseDetail(int $id): Response
	{
		return $this->connector->send(new ViewWarehouseDetail($id));
	}


	/**
	 * @param int $id ID of the Warehouse
	 */
	public function updateWarehouseDetail(int $id): Response
	{
		return $this->connector->send(new UpdateWarehouseDetail($id));
	}


	/**
	 * @param int $id ID of the Warehouse
	 */
	public function deleteWarehouse(int $id): Response
	{
		return $this->connector->send(new DeleteWarehouse($id));
	}
}
