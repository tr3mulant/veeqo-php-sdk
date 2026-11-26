<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\Payments\CreatePaymentData;
use TremulantTech\Veeqo\Requests\Payments\CreatePayment;
use TremulantTech\Veeqo\Veeqo;

class Payments
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * Marks an order as paid, moving it out of "Awaiting payment".
     */
    public function create(CreatePaymentData $data): void
    {
        $this->veeqo->send(new CreatePayment($data));
    }
}
