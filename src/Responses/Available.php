<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Available
{
    /**
     * @param  numeric-string|null  $totalNetCharge
     * @param  numeric-string|null  $totalGrossCharge
     * @param  numeric-string|null  $baseRate
     * @param  list<Charge>|null  $charges
     * @param  numeric-string|null  $weight
     * @param  list<ShippingServiceOption>|null  $shippingServiceOptions
     */
    public function __construct(
        public ?string $carrier = null,
        public ?string $name = null,
        public ?string $title = null,
        public ?string $shortTitle = null,
        public ?string $titleWithPrice = null,
        public ?string $totalNetCharge = null,
        public ?string $totalGrossCharge = null,
        public ?string $baseRate = null,
        public ?array $charges = null,
        public ?string $currency = null,
        public ?string $weight = null,
        public ?string $remoteShipmentId = null,
        public ?DateTimeImmutable $cutoff = null,
        public ?int $expectedDeliveryDays = null,
        public ?string $subCarrierId = null,
        public ?string $serviceCarrier = null,
        public ?array $shippingServiceOptions = null,
        public ?bool $hidden = null,
        public ?bool $ownAccount = null,
        public ?DateTimeImmutable $expiresAt = null,
        public ?DateTimeImmutable $deliveryPromiseDate = null,
        public ?bool $protected = null,
        public ?float $credits = null,
        public ?string $serviceId = null,
        public ?DocumentSpecification $documentSpecification = null,
    ) {}
}
