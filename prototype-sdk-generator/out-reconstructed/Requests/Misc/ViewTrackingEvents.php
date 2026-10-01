<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * View Tracking Events
 *
 * View tracking events for a shipment. Tracking events will be available if the label was purchased in
 * Veeqo.
 */
class ViewTrackingEvents extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/shipping/tracking_events/{$this->shipmentId}";
	}


	/**
	 * @param null|int $shipmentId The ID of the shipment to retrieve tracking events for.
	 */
	public function __construct(
		protected ?int $shipmentId = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['shipment_id' => $this->shipmentId]);
	}
}
