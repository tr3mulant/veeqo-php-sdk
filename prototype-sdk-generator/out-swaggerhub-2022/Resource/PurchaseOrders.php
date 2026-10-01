<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\PurchaseOrders\ListAllPurchaseOrders;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class PurchaseOrders extends BaseResource
{
	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param bool $showComplete
	 */
	public function listAllPurchaseOrders(?int $pageSize = null, ?int $page = null, ?bool $showComplete = null): Response
	{
		return $this->connector->send(new ListAllPurchaseOrders($pageSize, $page, $showComplete));
	}
}
