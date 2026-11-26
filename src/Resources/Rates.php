<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Requests\Rates\GetRate;
use TremulantTech\Veeqo\Responses\Rate;
use TremulantTech\Veeqo\Veeqo;

class Rates
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @param  list<int>|null  $shippingConfigurationIds
     */
    public function get(int $allocationId, ?bool $formatWithUnavailableQuotes = null, ?array $shippingConfigurationIds = null): Rate
    {
        return $this->veeqo->hydrate(Rate::class, $this->veeqo->send(new GetRate($allocationId, $formatWithUnavailableQuotes, $shippingConfigurationIds)));
    }
}
