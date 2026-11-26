<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\ProductPropertySpecifics\UpdateProductPropertySpecificData;
use TremulantTech\Veeqo\Requests\ProductPropertySpecifics\DeleteProductPropertySpecific;
use TremulantTech\Veeqo\Requests\ProductPropertySpecifics\GetProductPropertySpecific;
use TremulantTech\Veeqo\Requests\ProductPropertySpecifics\UpdateProductPropertySpecific;
use TremulantTech\Veeqo\Responses\ProductPropertySpecific;
use TremulantTech\Veeqo\Responses\ProductPropertySpecificSummary;
use TremulantTech\Veeqo\Veeqo;

class ProductPropertySpecifics
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function get(int $productId, int $id): ProductPropertySpecific
    {
        return $this->veeqo->hydrate(ProductPropertySpecific::class, $this->veeqo->send(new GetProductPropertySpecific($productId, $id)));
    }

    /**
     * Veeqo documents only the property name and value in the answer; get() has the rest.
     */
    public function update(int $productId, int $id, UpdateProductPropertySpecificData $data): ProductPropertySpecificSummary
    {
        return $this->veeqo->hydrate(ProductPropertySpecificSummary::class, $this->veeqo->send(new UpdateProductPropertySpecific($productId, $id, $data)));
    }

    public function delete(int $productId, int $id): void
    {
        $this->veeqo->send(new DeleteProductPropertySpecific($productId, $id));
    }
}
