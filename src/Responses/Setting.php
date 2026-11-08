<?php

namespace TremulantTech\Veeqo\Responses;

use TremulantTech\Veeqo\Enums\DimensionsUnit;

readonly class Setting
{
    public function __construct(
        public ?int $id = null,
        public ?bool $autoAllocationEnabled = null,
        public ?bool $automatedPackingFlow = null,
        public ?bool $chargeTaxesOnShippingRates = null,
        public ?string $currencyCode = null,
        public ?string $dateFormat = null,
        public ?DimensionsUnit $dimensionsUnit = null,
        public ?bool $linkSimilarAmazonListingsAcrossFbaAndFbm = null,
        public ?bool $linkSimilarAmazonListingsWithinMarketplace = null,
        public ?bool $linkSimilarAmazonListingsWithinRegion = null,
        public ?string $phoneNumber = null,
        public ?bool $phonePayments = null,
        public ?bool $pointOfSaleEnabled = null,
        public ?bool $pullFbaInboundStock = null,
        public ?bool $requireCompletePacking = null,
        public ?bool $stockChangeReasonRequired = null,
        public ?string $timezone = null,
        public ?string $weightUnit = null,
    ) {}
}
