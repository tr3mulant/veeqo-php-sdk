<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Shipments\CreateShipment;
use IronGate\Veeqo\Requests\Shipments\DeleteShipment;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Shipments extends BaseResource
{
	public function createShipment(): Response
	{
		return $this->connector->send(new CreateShipment());
	}


	/**
	 * @param int $id
	 */
	public function deleteShipment(int $id): Response
	{
		return $this->connector->send(new DeleteShipment($id));
	}
}
