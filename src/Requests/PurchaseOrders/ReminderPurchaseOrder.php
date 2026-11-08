<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use TremulantTech\Veeqo\Data\PurchaseOrders\ReminderPurchaseOrderData;
use TremulantTech\Veeqo\Veeqo;

/**
 * @see https://developers.veeqo.com/api/operations/email-purchase-order-reminders/
 */
class ReminderPurchaseOrder extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected ReminderPurchaseOrderData $data) {}

    public function resolveEndpoint(): string
    {
        return '/purchase_orders/reminder';
    }

    /**
     * Unenveloped, as the spec documents.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return Veeqo::normalize($this->data);
    }
}
