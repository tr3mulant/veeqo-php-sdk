<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Retrieve Shipping Rates
 *
 * Fetch shipping rates based on the provided parameters.
 */
class RetrieveShippingRates extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/shipping/rates/{$this->allocationId}";
	}


	/**
	 * @param float|int $allocationId The ID of the allocation of the order to retrieve shipping rates for.
	 * @param bool $fromAllocationPackage Must be set to true . Specifies whether to use dimensions from the already existing allocation package.
	 * @param null|bool $formatWithUnavailableQuotes Whether to include unavailable rates in the response. Defaults to false .
	 * @param null|float|int $shippingConfigurationIds IDs of linked carrier accounts to fetch rates for.
	 */
	public function __construct(
		protected float|int $allocationId,
		protected bool $fromAllocationPackage,
		protected ?bool $formatWithUnavailableQuotes = null,
		protected float|int|null $shippingConfigurationIds = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter([
			'from_allocation_package' => $this->fromAllocationPackage,
			'format_with_unavailable_quotes' => $this->formatWithUnavailableQuotes,
			'shipping_configuration_ids[]' => $this->shippingConfigurationIds,
		]);
	}
}
