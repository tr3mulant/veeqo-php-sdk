<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Products\CreateNewProduct;
use IronGate\Veeqo\Requests\Products\CreateNewProperty;
use IronGate\Veeqo\Requests\Products\DeleteProduct;
use IronGate\Veeqo\Requests\Products\ListAllProducts;
use IronGate\Veeqo\Requests\Products\RemovePropertyFromProduct;
use IronGate\Veeqo\Requests\Products\UpdateProductDetail;
use IronGate\Veeqo\Requests\Products\UpdatePropertyDetail;
use IronGate\Veeqo\Requests\Products\ViewProductDetail;
use IronGate\Veeqo\Requests\Products\ViewProperties;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Products extends BaseResource
{
	/**
	 * @param int $sinceId Restrict results to after specified ID
	 * @param int $warehouseId Restrict results to products with stock in specific warehouse
	 * @param string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param int $pageSize Amount of results per page
	 * @param int $page Page to show
	 * @param string $query Free text search
	 */
	public function listAllProducts(
		?int $sinceId = null,
		?int $warehouseId = null,
		?string $createdAtMin = null,
		?string $updatedAtMin = null,
		?int $pageSize = null,
		?int $page = null,
		?string $query = null,
	): Response
	{
		return $this->connector->send(new ListAllProducts($sinceId, $warehouseId, $createdAtMin, $updatedAtMin, $pageSize, $page, $query));
	}


	public function createNewProduct(): Response
	{
		return $this->connector->send(new CreateNewProduct());
	}


	/**
	 * @param int $productId ID of the Product
	 */
	public function viewProductDetail(int $productId): Response
	{
		return $this->connector->send(new ViewProductDetail($productId));
	}


	/**
	 * @param int $productId ID of the Product
	 */
	public function updateProductDetail(int $productId): Response
	{
		return $this->connector->send(new UpdateProductDetail($productId));
	}


	/**
	 * @param int $productId ID of the Product
	 */
	public function deleteProduct(int $productId): Response
	{
		return $this->connector->send(new DeleteProduct($productId));
	}


	/**
	 * @param int $productId ID of the Product
	 * @param int $propertyId ID of the Property
	 */
	public function viewProperties(int $productId, int $propertyId): Response
	{
		return $this->connector->send(new ViewProperties($productId, $propertyId));
	}


	/**
	 * @param int $productId ID of the Product
	 * @param int $propertyId ID of the Property
	 */
	public function updatePropertyDetail(int $productId, int $propertyId): Response
	{
		return $this->connector->send(new UpdatePropertyDetail($productId, $propertyId));
	}


	/**
	 * @param int $productId ID of the Product
	 * @param int $propertyId ID of the Property
	 */
	public function removePropertyFromProduct(int $productId, int $propertyId): Response
	{
		return $this->connector->send(new RemovePropertyFromProduct($productId, $propertyId));
	}


	public function createNewProperty(): Response
	{
		return $this->connector->send(new CreateNewProperty());
	}
}
