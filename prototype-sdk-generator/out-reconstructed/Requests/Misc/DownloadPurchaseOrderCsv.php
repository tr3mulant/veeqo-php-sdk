<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Download Purchase Order CSV
 *
 * 🚀 Paid plan only The purchase order document as a CSV attachment, generated synchronously. As
 * with the PDF operation the body is not JSON and the response is a file download, so navigate to it
 * rather than reading it as data.
 */
class DownloadPurchaseOrderCsv extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders/{$this->idCsv}";
	}


	/**
	 * @param int $id Purchase order ID. The .csv suffix is part of the path, not part of the id.
	 * @param null|string $xApiKey E.g. 123
	 */
	public function __construct(
		protected int $id,
		protected ?string $xApiKey = null,
	) {
	}


	public function defaultHeaders(): array
	{
		return array_filter(['x-api-key' => $this->xApiKey]);
	}
}
