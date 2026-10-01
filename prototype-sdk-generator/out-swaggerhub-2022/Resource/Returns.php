<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Returns\ShowReturnsOnOrder;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Returns extends BaseResource
{
	/**
	 * @param int $orderId ID of the Order
	 */
	public function showReturnsOnOrder(int $orderId): Response
	{
		return $this->connector->send(new ShowReturnsOnOrder($orderId));
	}
}
