<?php

namespace TremulantTech\Veeqo\Requests\PurchaseOrders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * @see https://developers.veeqo.com/api/operations/download-purchase-order-pdf/
 */
class DownloadPurchaseOrderPdf extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/purchase_orders/{$this->id}.pdf";
    }

    /**
     * Guzzle would resend the custom `x-api-key` header to a documented 302's other host.
     */
    protected function defaultConfig(): array
    {
        return ['allow_redirects' => false];
    }

    protected function defaultHeaders(): array
    {
        return ['Accept' => 'application/pdf'];
    }
}
