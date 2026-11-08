<?php

namespace TremulantTech\Veeqo\Requests\Rates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/retrieve-shipping-rates/
 */
class GetRate extends Request
{
    protected Method $method = Method::GET;

    /** @var array<string, string|list<int>> */
    private readonly array $filters;

    /**
     * @param  list<int>|null  $shippingConfigurationIds
     */
    public function __construct(
        protected int $allocationId,
        ?bool $formatWithUnavailableQuotes = null,
        ?array $shippingConfigurationIds = null,
    ) {
        $this->filters = array_filter([
            // The spec requires it and allows only true, so it is not a parameter.
            'from_allocation_package' => 'true',
            'format_with_unavailable_quotes' => match ($formatWithUnavailableQuotes) {
                null => null,
                true => 'true',
                false => 'false',
            },
            'shipping_configuration_ids' => $shippingConfigurationIds,
        ], fn (mixed $value) => $value !== null);
    }

    public function resolveEndpoint(): string
    {
        return "/shipping/rates/{$this->allocationId}";
    }

    protected function defaultQuery(): array
    {
        return $this->filters;
    }
}
