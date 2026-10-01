<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\StockEntries\ShowStockEntry;
use IronGate\Veeqo\Requests\StockEntries\UpdateStockEntry;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class StockEntries extends BaseResource
{
	/**
	 * @param float|int $sellableId Sellable ID
	 * @param float|int $warehouseId Warehouse ID
	 */
	public function showStockEntry(float|int $sellableId, float|int $warehouseId): Response
	{
		return $this->connector->send(new ShowStockEntry($sellableId, $warehouseId));
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param float|int $warehouseId Warehouse ID
	 */
	public function updateStockEntry(float|int $sellableId, float|int $warehouseId): Response
	{
		return $this->connector->send(new UpdateStockEntry($sellableId, $warehouseId));
	}
}
