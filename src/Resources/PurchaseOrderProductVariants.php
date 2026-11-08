<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\PurchaseOrderProductVariants\UpdatePurchaseOrderProductVariantData;
use TremulantTech\Veeqo\Requests\PurchaseOrderProductVariants\DeletePurchaseOrderProductVariant;
use TremulantTech\Veeqo\Requests\PurchaseOrderProductVariants\UpdatePurchaseOrderProductVariant;
use TremulantTech\Veeqo\Responses\PurchaseOrderProductVariant;
use TremulantTech\Veeqo\Veeqo;

class PurchaseOrderProductVariants
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function update(int $purchaseOrderId, int $id, UpdatePurchaseOrderProductVariantData $data): PurchaseOrderProductVariant
    {
        return $this->veeqo->hydrate(PurchaseOrderProductVariant::class, $this->veeqo->send(new UpdatePurchaseOrderProductVariant($purchaseOrderId, $id, $data)));
    }

    /**
     * The only way to remove a line item: purchaseOrders()->update() has no `_destroy`.
     */
    public function delete(int $purchaseOrderId, int $id): void
    {
        $this->veeqo->send(new DeletePurchaseOrderProductVariant($purchaseOrderId, $id));
    }
}
