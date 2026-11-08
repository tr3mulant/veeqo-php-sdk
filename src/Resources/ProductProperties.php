<?php

namespace TremulantTech\Veeqo\Resources;

use TremulantTech\Veeqo\Data\ProductProperties\CreateProductPropertyData;
use TremulantTech\Veeqo\Requests\ProductProperties\CreateProductProperty;
use TremulantTech\Veeqo\Responses\ProductProperty;
use TremulantTech\Veeqo\Veeqo;

class ProductProperties
{
    public function __construct(private readonly Veeqo $veeqo) {}

    public function create(CreateProductPropertyData $data): ProductProperty
    {
        return $this->veeqo->hydrate(ProductProperty::class, $this->veeqo->send(new CreateProductProperty($data)));
    }
}
