<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Misc\BulkTagging;
use IronGate\Veeqo\Requests\Misc\BulkUntagging;
use IronGate\Veeqo\Requests\Misc\CancelOrder;
use IronGate\Veeqo\Requests\Misc\CreateBundle;
use IronGate\Veeqo\Requests\Misc\CreateBundleContent;
use IronGate\Veeqo\Requests\Misc\CreateChannelSellable;
use IronGate\Veeqo\Requests\Misc\CreateCustomer;
use IronGate\Veeqo\Requests\Misc\CreateDeliveryMethod;
use IronGate\Veeqo\Requests\Misc\CreateNewAllocation;
use IronGate\Veeqo\Requests\Misc\CreateNewOrder;
use IronGate\Veeqo\Requests\Misc\CreateNewOrderNote;
use IronGate\Veeqo\Requests\Misc\CreateNewProduct;
use IronGate\Veeqo\Requests\Misc\CreateNewProperty;
use IronGate\Veeqo\Requests\Misc\CreateNewSupplier;
use IronGate\Veeqo\Requests\Misc\CreateNewTag;
use IronGate\Veeqo\Requests\Misc\CreatePayment;
use IronGate\Veeqo\Requests\Misc\CreatePurchaseOrder;
use IronGate\Veeqo\Requests\Misc\CreateShipment;
use IronGate\Veeqo\Requests\Misc\CreateStore;
use IronGate\Veeqo\Requests\Misc\CreateWarehouse;
use IronGate\Veeqo\Requests\Misc\Delete;
use IronGate\Veeqo\Requests\Misc\DeleteBundleContent;
use IronGate\Veeqo\Requests\Misc\DeleteDeliveryMethod;
use IronGate\Veeqo\Requests\Misc\DeleteProduct;
use IronGate\Veeqo\Requests\Misc\DeletePurchaseOrderLineItem;
use IronGate\Veeqo\Requests\Misc\DeleteSupplier;
use IronGate\Veeqo\Requests\Misc\DownloadPurchaseOrderCsv;
use IronGate\Veeqo\Requests\Misc\DownloadPurchaseOrderPdf;
use IronGate\Veeqo\Requests\Misc\EmailPurchaseOrderReminders;
use IronGate\Veeqo\Requests\Misc\GetPurchaseOrder;
use IronGate\Veeqo\Requests\Misc\ListAllCustomers;
use IronGate\Veeqo\Requests\Misc\ListAllDeliveryMethods;
use IronGate\Veeqo\Requests\Misc\ListAllOrders;
use IronGate\Veeqo\Requests\Misc\ListAllProducts;
use IronGate\Veeqo\Requests\Misc\ListAllPurchaseOrders;
use IronGate\Veeqo\Requests\Misc\ListAllStores;
use IronGate\Veeqo\Requests\Misc\ListAllSuppliers;
use IronGate\Veeqo\Requests\Misc\ListAllTags;
use IronGate\Veeqo\Requests\Misc\ListAllWarehouses;
use IronGate\Veeqo\Requests\Misc\PurchaseShippingLabels;
use IronGate\Veeqo\Requests\Misc\RemovePropertyFromProduct;
use IronGate\Veeqo\Requests\Misc\RetrieveBundleContent;
use IronGate\Veeqo\Requests\Misc\RetrieveBundleDetail;
use IronGate\Veeqo\Requests\Misc\RetrieveShippingLabels;
use IronGate\Veeqo\Requests\Misc\RetrieveShippingRates;
use IronGate\Veeqo\Requests\Misc\ShowReturnsOnOrder;
use IronGate\Veeqo\Requests\Misc\ShowStockEntry;
use IronGate\Veeqo\Requests\Misc\UpdateAllocationDetail;
use IronGate\Veeqo\Requests\Misc\UpdateAllocationPackage;
use IronGate\Veeqo\Requests\Misc\UpdateBundleContent;
use IronGate\Veeqo\Requests\Misc\UpdateCompanyDetail;
use IronGate\Veeqo\Requests\Misc\UpdateCustomerDetail;
use IronGate\Veeqo\Requests\Misc\UpdateDeliveryMethodDetail;
use IronGate\Veeqo\Requests\Misc\UpdateLineItemNotes;
use IronGate\Veeqo\Requests\Misc\UpdateOrderDetail;
use IronGate\Veeqo\Requests\Misc\UpdateProductDetail;
use IronGate\Veeqo\Requests\Misc\UpdatePropertyDetail;
use IronGate\Veeqo\Requests\Misc\UpdatePurchaseOrder;
use IronGate\Veeqo\Requests\Misc\UpdatePurchaseOrderLineItem;
use IronGate\Veeqo\Requests\Misc\UpdateStockEntry;
use IronGate\Veeqo\Requests\Misc\UpdateStoreDetail;
use IronGate\Veeqo\Requests\Misc\UpdateSupplierDetail;
use IronGate\Veeqo\Requests\Misc\UpdateWarehouseDetail;
use IronGate\Veeqo\Requests\Misc\ViewCompanyDetail;
use IronGate\Veeqo\Requests\Misc\ViewCustomerDetail;
use IronGate\Veeqo\Requests\Misc\ViewDeliveryMethod;
use IronGate\Veeqo\Requests\Misc\ViewOrderDetail;
use IronGate\Veeqo\Requests\Misc\ViewProductDetail;
use IronGate\Veeqo\Requests\Misc\ViewProperties;
use IronGate\Veeqo\Requests\Misc\ViewStoreDetail;
use IronGate\Veeqo\Requests\Misc\ViewSupplierDetail;
use IronGate\Veeqo\Requests\Misc\ViewTagDetail;
use IronGate\Veeqo\Requests\Misc\ViewTrackingEvents;
use IronGate\Veeqo\Requests\Misc\ViewWarehouseDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Misc extends BaseResource
{
	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function bulkTagging(?string $xApiKey = null): Response
	{
		return $this->connector->send(new BulkTagging($xApiKey));
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function bulkUntagging(?string $xApiKey = null): Response
	{
		return $this->connector->send(new BulkUntagging($xApiKey));
	}


	/**
	 * @param int $orderId Order ID
	 * @param string $xApiKey E.g. 123
	 */
	public function cancelOrder(int $orderId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new CancelOrder($orderId, $xApiKey));
	}


	public function createBundle(): Response
	{
		return $this->connector->send(new CreateBundle());
	}


	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $query Free text search
	 * @param string $customerType Filter by customer type
	 */
	public function listAllCustomers(
		?int $pageSize = null,
		?int $page = null,
		?string $query = null,
		?string $customerType = null,
	): Response
	{
		return $this->connector->send(new ListAllCustomers($pageSize, $page, $query, $customerType));
	}


	public function createCustomer(): Response
	{
		return $this->connector->send(new CreateCustomer());
	}


	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 */
	public function listAllDeliveryMethods(?int $pageSize = null, ?int $page = null): Response
	{
		return $this->connector->send(new ListAllDeliveryMethods($pageSize, $page));
	}


	public function createDeliveryMethod(): Response
	{
		return $this->connector->send(new CreateDeliveryMethod());
	}


	/**
	 * @param int $orderId Order ID
	 * @param string $xApiKey E.g. 123
	 */
	public function createNewAllocation(int $orderId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreateNewAllocation($orderId, $xApiKey));
	}


	/**
	 * @param int $sinceId Restrict results to after specified ID
	 * @param string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param array $channelIds Only show orders from the given channel ID(s). Accepts a single ID or an array. For multiple IDs, use bracket array form, for example channel_ids[]=12345&channel_ids[]=67890 .
	 * @param string $createdafter Only show orders created on or after this date.
	 * @param string $createdbefore Only show orders created on or before this date (the whole day is included). Combine with created[after] to bound a date range server-side instead of scanning from created_at_min onwards.
	 * @param string $dueafter Only show orders due on or after this date.
	 * @param string $duebefore Only show orders due on or before this date (the whole day is included).
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $query Free text search
	 * @param string $status Order Status
	 * @param string $tags Restrict results to orders with a tag of the name provided
	 * @param int $allocatedAt Restrict results to orders allocated at a specific warehouse
	 * @param string $xApiKey E.g. 123
	 */
	public function listAllOrders(
		?int $sinceId = null,
		?string $createdAtMin = null,
		?string $updatedAtMin = null,
		?array $channelIds = null,
		?string $createdafter = null,
		?string $createdbefore = null,
		?string $dueafter = null,
		?string $duebefore = null,
		?int $pageSize = null,
		?int $page = null,
		?string $query = null,
		?string $status = null,
		?string $tags = null,
		?int $allocatedAt = null,
		?string $xApiKey = null,
	): Response
	{
		return $this->connector->send(new ListAllOrders($sinceId, $createdAtMin, $updatedAtMin, $channelIds, $createdafter, $createdbefore, $dueafter, $duebefore, $pageSize, $page, $query, $status, $tags, $allocatedAt, $xApiKey));
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function createNewOrder(?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreateNewOrder($xApiKey));
	}


	/**
	 * @param int $orderId Order ID
	 * @param string $xApiKey E.g. 123
	 */
	public function createNewOrderNote(int $orderId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreateNewOrderNote($orderId, $xApiKey));
	}


	/**
	 * @param int $sinceId Show only products with an ID greater than this number.
	 * @param int $warehouseId Restrict results to products with stock in specific location.
	 * @param string $createdAtMin Show entities created after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param string $updatedAtMin Show entities updated after date (format: YYYY-MM-DD HH:MM:SS)
	 * @param int $pageSize Number of results per page. Maximum 100.
	 * @param int $page The page to show.
	 * @param string $query Search for products by name or SKU code.
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
	 * @param string $xApiKey E.g. 123
	 */
	public function createNewProperty(?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreateNewProperty($xApiKey));
	}


	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $xApiKey E.g. 123
	 */
	public function listAllSuppliers(?int $pageSize = null, ?int $page = null, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new ListAllSuppliers($pageSize, $page, $xApiKey));
	}


	public function createNewSupplier(): Response
	{
		return $this->connector->send(new CreateNewSupplier());
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function listAllTags(?string $xApiKey = null): Response
	{
		return $this->connector->send(new ListAllTags($xApiKey));
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function createNewTag(?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreateNewTag($xApiKey));
	}


	public function createShipment(): Response
	{
		return $this->connector->send(new CreateShipment());
	}


	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $xApiKey E.g. 123
	 */
	public function listAllStores(?int $pageSize = null, ?int $page = null, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new ListAllStores($pageSize, $page, $xApiKey));
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function createStore(?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreateStore($xApiKey));
	}


	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $externalId Show only warehouses with this external ID.
	 */
	public function listAllWarehouses(?int $pageSize = null, ?int $page = null, ?string $externalId = null): Response
	{
		return $this->connector->send(new ListAllWarehouses($pageSize, $page, $externalId));
	}


	public function createWarehouse(): Response
	{
		return $this->connector->send(new CreateWarehouse());
	}


	/**
	 * @param float|int $kitId ID of the bundle.
	 */
	public function createBundleContent(float|int $kitId): Response
	{
		return $this->connector->send(new CreateBundleContent($kitId));
	}


	public function createChannelSellable(): Response
	{
		return $this->connector->send(new CreateChannelSellable());
	}


	public function createPayment(): Response
	{
		return $this->connector->send(new CreatePayment());
	}


	/**
	 * @param int $pageSize Results per page. Default 25, silently capped at 100.
	 * @param int $page Page to show. Totals are returned in the X-Total-Count and X-Total-Pages-Count response headers.
	 * @param string $query Free-text search across supplier name and post code, purchase order number, reference number, and line-item variant title, SKU, UPC and supplier reference. Substring matching. Search-index backed, so a just-written purchase order can be missing here - read it back with Get Purchase Order instead.
	 * @param string $state Lifecycle state, or a received-status aggregate. An unrecognised value is a 400. There is no default, so drafts are included unless you filter. not_received , partially_received , fully_received , past_due and all are FILTER-ONLY values and never appear in a response state .
	 * @param int $supplierId Only purchase orders for this supplier.
	 * @param int $destinationWarehouseId Only purchase orders delivering to this warehouse.
	 * @param int $productVariantId Only ACTIVE purchase orders containing this variant (sellable) id.
	 * @param string $productVariantUpcCode Only purchase orders containing a line item with this UPC.
	 * @param string $referenceNumber Exact match on the purchase order reference number.
	 * @param string $createdafter Created on or after this date. A range where after > before is a 400.
	 * @param string $createdbefore Created on or before this date (inclusive of that whole day).
	 * @param string $createdAtMin Created at or after this timestamp.
	 * @param string $updatedAtMin Updated at or after this timestamp.
	 * @param int $sinceId Only purchase orders with an id greater than this.
	 * @param string $sortBy Field to sort on. Only supplier_name , created_at , expected_date , estimated_delivery_days and sort_by_received_percent are honoured; any other value is IGNORED silently and the results come back created_at descending, which is also the default when this is omitted.
	 * @param string $order Sort direction. Only used alongside a recognised sort_by .
	 * @param string $xApiKey E.g. 123
	 */
	public function listAllPurchaseOrders(
		?int $pageSize = null,
		?int $page = null,
		?string $query = null,
		?string $state = null,
		?int $supplierId = null,
		?int $destinationWarehouseId = null,
		?int $productVariantId = null,
		?string $productVariantUpcCode = null,
		?string $referenceNumber = null,
		?string $createdafter = null,
		?string $createdbefore = null,
		?string $createdAtMin = null,
		?string $updatedAtMin = null,
		?int $sinceId = null,
		?string $sortBy = null,
		?string $order = null,
		?string $xApiKey = null,
	): Response
	{
		return $this->connector->send(new ListAllPurchaseOrders($pageSize, $page, $query, $state, $supplierId, $destinationWarehouseId, $productVariantId, $productVariantUpcCode, $referenceNumber, $createdafter, $createdbefore, $createdAtMin, $updatedAtMin, $sinceId, $sortBy, $order, $xApiKey));
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function createPurchaseOrder(?string $xApiKey = null): Response
	{
		return $this->connector->send(new CreatePurchaseOrder($xApiKey));
	}


	/**
	 * @param int $orderId ID of the Order
	 * @param int $allocationId ID of the Allocation
	 */
	public function updateAllocationDetail(int $orderId, int $allocationId): Response
	{
		return $this->connector->send(new UpdateAllocationDetail($orderId, $allocationId));
	}


	/**
	 * @param int $orderId ID of the Order
	 * @param int $allocationId ID of the Allocation
	 * @param string $xApiKey E.g. 123
	 */
	public function delete(int $orderId, int $allocationId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new Delete($orderId, $allocationId, $xApiKey));
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
	 * @param float|int $kitId ID of the bundle.
	 * @param float|int $kitContentId The ID of the kit content. This is different from the product variant ID.
	 */
	public function retrieveBundleContent(float|int $kitId, float|int $kitContentId): Response
	{
		return $this->connector->send(new RetrieveBundleContent($kitId, $kitContentId));
	}


	/**
	 * @param float|int $kitId ID of the bundle.
	 * @param float|int $kitContentId The ID of the kit content. This is different from the product variant ID.
	 */
	public function updateBundleContent(float|int $kitId, float|int $kitContentId): Response
	{
		return $this->connector->send(new UpdateBundleContent($kitId, $kitContentId));
	}


	/**
	 * @param float|int $kitId ID of the bundle.
	 * @param float|int $kitContentId The ID of the kit content. This is different from the product variant ID.
	 */
	public function deleteBundleContent(float|int $kitId, float|int $kitContentId): Response
	{
		return $this->connector->send(new DeleteBundleContent($kitId, $kitContentId));
	}


	/**
	 * @param int $id
	 */
	public function viewStoreDetail(int $id): Response
	{
		return $this->connector->send(new ViewStoreDetail($id));
	}


	/**
	 * @param int $id
	 * @param string $xApiKey E.g. 123
	 */
	public function updateStoreDetail(int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new UpdateStoreDetail($id, $xApiKey));
	}


	/**
	 * @todo Fix duplicated method name
	 * @param int $id
	 */
	public function deleteDuplicate1(int $id): Response
	{
		return $this->connector->send(new Delete($id));
	}


	/**
	 * @param int $id ID of the delivery method to retrieve.
	 */
	public function viewDeliveryMethod(int $id): Response
	{
		return $this->connector->send(new ViewDeliveryMethod($id));
	}


	/**
	 * @param int $id ID of the delivery method to retrieve.
	 */
	public function updateDeliveryMethodDetail(int $id): Response
	{
		return $this->connector->send(new UpdateDeliveryMethodDetail($id));
	}


	/**
	 * @param int $id ID of the delivery method to delete.
	 */
	public function deleteDeliveryMethod(int $id): Response
	{
		return $this->connector->send(new DeleteDeliveryMethod($id));
	}


	/**
	 * @param int $purchaseOrderId
	 * @param int $id Line item (purchase_order_product_variant) id.
	 * @param string $xApiKey E.g. 123
	 */
	public function updatePurchaseOrderLineItem(int $purchaseOrderId, int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new UpdatePurchaseOrderLineItem($purchaseOrderId, $id, $xApiKey));
	}


	/**
	 * @param int $purchaseOrderId
	 * @param int $id Line item (purchase_order_product_variant) id.
	 * @param string $xApiKey E.g. 123
	 */
	public function deletePurchaseOrderLineItem(int $purchaseOrderId, int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new DeletePurchaseOrderLineItem($purchaseOrderId, $id, $xApiKey));
	}


	/**
	 * @todo Fix duplicated method name
	 * @param int $id
	 */
	public function deleteDuplicate2(int $id): Response
	{
		return $this->connector->send(new Delete($id));
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function viewSupplierDetail(int $id): Response
	{
		return $this->connector->send(new ViewSupplierDetail($id));
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function updateSupplierDetail(int $id): Response
	{
		return $this->connector->send(new UpdateSupplierDetail($id));
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function deleteSupplier(int $id): Response
	{
		return $this->connector->send(new DeleteSupplier($id));
	}


	/**
	 * @param int $tagId ID of the Tag
	 */
	public function viewTagDetail(int $tagId): Response
	{
		return $this->connector->send(new ViewTagDetail($tagId));
	}


	/**
	 * @todo Fix duplicated method name
	 * @param int $tagId ID of the Tag
	 */
	public function deleteDuplicate3(int $tagId): Response
	{
		return $this->connector->send(new Delete($tagId));
	}


	/**
	 * @param int $id ID of the Warehouse
	 */
	public function viewWarehouseDetail(int $id): Response
	{
		return $this->connector->send(new ViewWarehouseDetail($id));
	}


	/**
	 * @param int $id ID of the Warehouse
	 */
	public function updateWarehouseDetail(int $id): Response
	{
		return $this->connector->send(new UpdateWarehouseDetail($id));
	}


	/**
	 * @todo Fix duplicated method name
	 * @param int $id ID of the Warehouse
	 */
	public function deleteDuplicate4(int $id): Response
	{
		return $this->connector->send(new Delete($id));
	}


	/**
	 * @param int $id Purchase order ID. The .csv suffix is part of the path, not part of the id.
	 * @param string $xApiKey E.g. 123
	 */
	public function downloadPurchaseOrderCsv(int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new DownloadPurchaseOrderCsv($id, $xApiKey));
	}


	/**
	 * @param int $id Purchase order ID. The .pdf suffix is part of the path, not part of the id.
	 * @param string $xApiKey E.g. 123
	 */
	public function downloadPurchaseOrderPdf(int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new DownloadPurchaseOrderPdf($id, $xApiKey));
	}


	/**
	 * @param string $xApiKey E.g. 123
	 */
	public function emailPurchaseOrderReminders(?string $xApiKey = null): Response
	{
		return $this->connector->send(new EmailPurchaseOrderReminders($xApiKey));
	}


	/**
	 * @param int $id Purchase order ID.
	 * @param string $xApiKey E.g. 123
	 */
	public function getPurchaseOrder(int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new GetPurchaseOrder($id, $xApiKey));
	}


	/**
	 * @param int $id Purchase order ID.
	 * @param string $xApiKey E.g. 123
	 */
	public function updatePurchaseOrder(int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new UpdatePurchaseOrder($id, $xApiKey));
	}


	public function purchaseShippingLabels(): Response
	{
		return $this->connector->send(new PurchaseShippingLabels());
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
	 * @param string $xApiKey E.g. 123
	 */
	public function updatePropertyDetail(int $productId, int $propertyId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new UpdatePropertyDetail($productId, $propertyId, $xApiKey));
	}


	/**
	 * @param int $productId ID of the Product
	 * @param int $propertyId ID of the Property
	 */
	public function removePropertyFromProduct(int $productId, int $propertyId): Response
	{
		return $this->connector->send(new RemovePropertyFromProduct($productId, $propertyId));
	}


	/**
	 * @param float|int $kitId ID of the bundle.
	 */
	public function retrieveBundleDetail(float|int $kitId): Response
	{
		return $this->connector->send(new RetrieveBundleDetail($kitId));
	}


	/**
	 * @param string $shipmentIds Comma-separated list of shipment IDs to retrieve labels for.
	 * @param string $format The extension of the format to return the shipping label as.
	 */
	public function retrieveShippingLabels(string $shipmentIds, string $format): Response
	{
		return $this->connector->send(new RetrieveShippingLabels($shipmentIds, $format));
	}


	/**
	 * @param float|int $allocationId The ID of the allocation of the order to retrieve shipping rates for.
	 * @param bool $fromAllocationPackage Must be set to true . Specifies whether to use dimensions from the already existing allocation package.
	 * @param bool $formatWithUnavailableQuotes Whether to include unavailable rates in the response. Defaults to false .
	 * @param float|int $shippingConfigurationIds IDs of linked carrier accounts to fetch rates for.
	 */
	public function retrieveShippingRates(
		float|int $allocationId,
		bool $fromAllocationPackage,
		?bool $formatWithUnavailableQuotes = null,
		float|int|null $shippingConfigurationIds = null,
	): Response
	{
		return $this->connector->send(new RetrieveShippingRates($allocationId, $fromAllocationPackage, $formatWithUnavailableQuotes, $shippingConfigurationIds));
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param float|int $warehouseId Warehouse ID
	 * @param string $xApiKey E.g. 123
	 */
	public function showStockEntry(float|int $sellableId, float|int $warehouseId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new ShowStockEntry($sellableId, $warehouseId, $xApiKey));
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param float|int $warehouseId Warehouse ID
	 */
	public function updateStockEntry(float|int $sellableId, float|int $warehouseId): Response
	{
		return $this->connector->send(new UpdateStockEntry($sellableId, $warehouseId));
	}


	/**
	 * @param float|int $sellableId Sellable ID
	 * @param string $xApiKey E.g. 123
	 */
	public function showReturnsOnOrder(float|int $sellableId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new ShowReturnsOnOrder($sellableId, $xApiKey));
	}


	/**
	 * @param int $allocationId ID of the Allocation
	 */
	public function updateAllocationPackage(int $allocationId): Response
	{
		return $this->connector->send(new UpdateAllocationPackage($allocationId));
	}


	public function viewCompanyDetail(): Response
	{
		return $this->connector->send(new ViewCompanyDetail());
	}


	public function updateCompanyDetail(): Response
	{
		return $this->connector->send(new UpdateCompanyDetail());
	}


	/**
	 * @param int $id ID of the customer.
	 * @param string $xApiKey E.g. 123
	 */
	public function viewCustomerDetail(int $id, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new ViewCustomerDetail($id, $xApiKey));
	}


	/**
	 * @param int $id ID of the customer.
	 */
	public function updateCustomerDetail(int $id): Response
	{
		return $this->connector->send(new UpdateCustomerDetail($id));
	}


	/**
	 * @param int $id ID of the line item to update.
	 */
	public function updateLineItemNotes(int $id): Response
	{
		return $this->connector->send(new UpdateLineItemNotes($id));
	}


	/**
	 * @param int $orderId ID of the Order
	 */
	public function viewOrderDetail(int $orderId): Response
	{
		return $this->connector->send(new ViewOrderDetail($orderId));
	}


	/**
	 * @param int $orderId ID of the Order
	 * @param string $xApiKey E.g. 123
	 */
	public function updateOrderDetail(int $orderId, ?string $xApiKey = null): Response
	{
		return $this->connector->send(new UpdateOrderDetail($orderId, $xApiKey));
	}


	/**
	 * @param int $shipmentId The ID of the shipment to retrieve tracking events for.
	 */
	public function viewTrackingEvents(?int $shipmentId = null): Response
	{
		return $this->connector->send(new ViewTrackingEvents($shipmentId));
	}
}
