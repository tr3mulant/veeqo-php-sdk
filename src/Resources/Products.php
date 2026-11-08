<?php

namespace TremulantTech\Veeqo\Resources;

use DateTimeInterface;
use Generator;
use LogicException;
use Saloon\Http\Response;
use TremulantTech\Veeqo\Data\Products\CreateProductData;
use TremulantTech\Veeqo\Data\Products\UpdateProductData;
use TremulantTech\Veeqo\Requests\Products\CreateProduct;
use TremulantTech\Veeqo\Requests\Products\DeleteProduct;
use TremulantTech\Veeqo\Requests\Products\GetProduct;
use TremulantTech\Veeqo\Requests\Products\ListProducts;
use TremulantTech\Veeqo\Requests\Products\UpdateProduct;
use TremulantTech\Veeqo\Responses\Product;
use TremulantTech\Veeqo\Veeqo;

class Products
{
    public function __construct(private readonly Veeqo $veeqo) {}

    /**
     * @return Generator<int, Product>
     */
    public function list(
        ?int $sinceId = null,
        ?int $warehouseId = null,
        ?DateTimeInterface $createdAtMin = null,
        ?DateTimeInterface $updatedAtMin = null,
        ?string $query = null,
    ): Generator {
        foreach ($this->veeqo->paginate(new ListProducts($sinceId, $warehouseId, $createdAtMin, $updatedAtMin, $query)) as $page) {
            if (! $page instanceof Response) {
                throw new LogicException('Expected a synchronous page');
            }
            foreach ($this->veeqo->hydrateList(Product::class, $page) as $product) {
                yield $product;
            }
        }
    }

    public function get(int $id): Product
    {
        return $this->veeqo->hydrate(Product::class, $this->veeqo->send(new GetProduct($id)));
    }

    public function create(CreateProductData $data): Product
    {
        return $this->veeqo->hydrate(Product::class, $this->veeqo->send(new CreateProduct($data)));
    }

    public function update(int $id, UpdateProductData $data): Product
    {
        return $this->veeqo->hydrate(Product::class, $this->veeqo->send(new UpdateProduct($id, $data)));
    }

    public function delete(int $id): void
    {
        $this->veeqo->send(new DeleteProduct($id));
    }
}
