<?php

namespace TremulantTech\Veeqo\Data\Shipping;

/**
 * Copy the chosen quote's fields from `rates()->get()`. Its `shipping_service_options` keys
 * (`value_added_service__*`, `additional_input__*`) are untyped: add them to the request's
 * `shipment` body yourself (ADR 0010).
 */
readonly class PurchaseLabelsData
{
    /**
     * @param  numeric-string  $totalNetCharge
     * @param  numeric-string  $baseRate
     * @param  numeric-string|null  $liabilityAmount
     */
    public function __construct(
        public string $carrier,
        public int $allocationId,
        public string $remoteShipmentId,
        public string $serviceType,
        public string $subCarrierId,
        public string $totalNetCharge,
        public string $baseRate,
        public ?string $carrierId = null,
        public ?bool $notifyCustomer = null,
        public ?bool $updateRemoteOrder = null,
        public ?string $serviceCarrier = null,
        public ?string $paymentMethodId = null,
        public ?string $liabilityAmount = null,
        public ?bool $tryInboundLabel = null,
    ) {}
}
