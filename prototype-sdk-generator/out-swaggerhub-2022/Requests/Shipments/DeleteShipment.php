<?php

namespace IronGate\Veeqo\Requests\Shipments;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Shipment
 */
class DeleteShipment extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/shipments/{$this->id}";
	}


	/**
	 * @param int $id
	 */
	public function __construct(
		protected int $id,
	) {
	}
}
