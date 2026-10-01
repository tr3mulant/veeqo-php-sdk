<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieve Shipping Labels
 *
 * Fetch shipping labels for the specified shipment IDs.
 */
class RetrieveShippingLabels extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/shipping/labels:format?shipment_ids[]=:shipment_ids";
	}


	/**
	 * @param string $shipmentIds Comma-separated list of shipment IDs to retrieve labels for.
	 * @param string $format The extension of the format to return the shipping label as.
	 */
	public function __construct(
		protected string $shipmentIds,
		protected string $format,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['shipment_ids' => $this->shipmentIds, 'format' => $this->format]);
	}


	public function defaultHeaders(): array
	{
		return array_filter([]);
	}
}
