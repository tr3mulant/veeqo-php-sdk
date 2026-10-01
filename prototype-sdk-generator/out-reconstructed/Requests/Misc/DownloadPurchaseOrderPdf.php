<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Download Purchase Order PDF
 *
 * 🚀 Paid plan only Renders the purchase order document synchronously and returns it as a PDF
 * attachment. There is no queue, no job to poll, no asset to fetch afterwards and no notification -
 * never tell a user the document will arrive somewhere. The response body is binary, so it cannot be
 * parsed as JSON. Send a user here by NAVIGATING (a link or a download control), not by reading the
 * body as data. The template is chosen by the server: a Veeqo-owned purchase order template, or the
 * supplier’s own purchase_order_template on a plan with customizable templates. There is nothing to
 * select and no template parameter.
 */
class DownloadPurchaseOrderPdf extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders/{$this->idPdf}";
	}


	/**
	 * @param int $id Purchase order ID. The .pdf suffix is part of the path, not part of the id.
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
