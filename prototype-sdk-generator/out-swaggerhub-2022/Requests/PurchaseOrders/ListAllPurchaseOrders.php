<?php

namespace IronGate\Veeqo\Requests\PurchaseOrders;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Purchase Orders
 */
class ListAllPurchaseOrders extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/purchase_orders";
	}


	/**
	 * @param null|int $pageSize Amount of results
	 * @param null|int $page Page to show
	 * @param null|bool $showComplete
	 */
	public function __construct(
		protected ?int $pageSize = null,
		protected ?int $page = null,
		protected ?bool $showComplete = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['page_size' => $this->pageSize, 'page' => $this->page, 'show_complete' => $this->showComplete]);
	}
}
