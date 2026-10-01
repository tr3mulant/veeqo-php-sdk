<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Channel extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		#[MapName('type_code')]
		public ?string $typeCode = null,
		#[MapName('created_by_id')]
		public int|float|null $createdById = null,
		public ?string $name = null,
		#[MapName('short_name')]
		public ?string $shortName = null,
		#[MapName('currency_code')]
		public ?string $currencyCode = null,
		public ?string $state = null,
		#[MapName('ebay_site_code_id')]
		public int|float|null $ebaySiteCodeId = null,
		public ?string $country = null,
		public ?string $region = null,
		public ?string $city = null,
		#[MapName('address_line_1')]
		public ?string $addressLine1 = null,
		#[MapName('address_line_2')]
		public ?string $addressLine2 = null,
		#[MapName('post_code')]
		public ?string $postCode = null,
		#[MapName('pull_pending_orders')]
		public ?bool $pullPendingOrders = null,
		#[MapName('default_send_shipment_email')]
		public ?bool $defaultSendShipmentEmail = null,
		#[MapName('automatic_product_linking_disabled')]
		public ?bool $automaticProductLinkingDisabled = null,
		#[MapName('update_remote_order')]
		public ?bool $updateRemoteOrder = null,
		#[MapName('create_product_if_unmatched')]
		public ?bool $createProductIfUnmatched = null,
		#[MapName('skip_title_matching')]
		public ?bool $skipTitleMatching = null,
		public ?string $email = null,
		#[MapName('skip_fba_orders_and_products')]
		public ?bool $skipFbaOrdersAndProducts = null,
		#[MapName('pull_stock_level_required')]
		public ?bool $pullStockLevelRequired = null,
		#[MapName('pull_product_properties')]
		public ?bool $pullProductProperties = null,
		#[MapName('pull_historical_orders')]
		public ?bool $pullHistoricalOrders = null,
		#[MapName('send_notification_emails_to_customers')]
		public ?bool $sendNotificationEmailsToCustomers = null,
		#[MapName('end_ebay_listing_on_out_of_stock')]
		public ?bool $endEbayListingOnOutOfStock = null,
		#[MapName('update_product_attributes')]
		public ?bool $updateProductAttributes = null,
		#[MapName('max_qty_to_advert')]
		public int|float|null $maxQtyToAdvert = null,
		#[MapName('min_threshold_qty')]
		public int|float|null $minThresholdQty = null,
		#[MapName('percent_of_qty')]
		public int|float|null $percentOfQty = null,
		#[MapName('always_set_qty')]
		public int|float|null $alwaysSetQty = null,
		#[MapName('veeqo_dictates_stock_level')]
		public ?bool $veeqoDictatesStockLevel = null,
		#[MapName('with_fba')]
		public ?bool $withFba = null,
		#[MapName('first_sync_finish_notice_marked_as_read')]
		public ?bool $firstSyncFinishNoticeMarkedAsRead = null,
		#[MapName('pull_unpaid_shopify_orders')]
		public ?bool $pullUnpaidShopifyOrders = null,
		#[MapName('create_product_on_ended_listings')]
		public ?bool $createProductOnEndedListings = null,
		#[MapName('link_to_products_linked_to_current_channel')]
		public ?bool $linkToProductsLinkedToCurrentChannel = null,
		#[MapName('link_with_similar_listings_by_sku')]
		public ?bool $linkWithSimilarListingsBySku = null,
		#[MapName('import_cost_price')]
		public ?bool $importCostPrice = null,
		#[MapName('veeqo_dictates_price')]
		public ?bool $veeqoDictatesPrice = null,
		#[MapName('keep_inventory_tracking_value')]
		public ?bool $keepInventoryTrackingValue = null,
		#[MapName('amazon_fulfillment_enabled')]
		public ?bool $amazonFulfillmentEnabled = null,
		#[MapName('import_product_tags')]
		public ?bool $importProductTags = null,
		#[MapName('import_product_brands')]
		public ?bool $importProductBrands = null,
		#[MapName('import_additional_payment_details')]
		public ?bool $importAdditionalPaymentDetails = null,
		#[MapName('import_order_tags')]
		public ?bool $importOrderTags = null,
		#[MapName('authorized_at_remote_store')]
		public ?bool $authorizedAtRemoteStore = null,
		#[MapName('pull_product_images')]
		public ?bool $pullProductImages = null,
		#[MapName('routing_order_type')]
		public int|float|null $routingOrderType = null,
		#[MapName('is_master')]
		public ?bool $isMaster = null,
		public ?object $warehouse = null,
		public ?array $warehouses = null,
		#[MapName('stock_level_update_requests')]
		public ?array $stockLevelUpdateRequests = null,
		#[MapName('channel_warehouses')]
		public ?array $channelWarehouses = null,
		#[MapName('channel_ranked_warehouses')]
		public ?array $channelRankedWarehouses = null,
		#[MapName('channel_near_warehouses')]
		public ?array $channelNearWarehouses = null,
		#[MapName('default_warehouse')]
		public ?Warehouse $defaultWarehouse = null,
		public ?bool $remote = null,
		#[MapName('disabled_push_of_stock_level')]
		public ?bool $disabledPushOfStockLevel = null,
		#[MapName('remote_warehouses_exist')]
		public ?bool $remoteWarehousesExist = null,
	) {
	}
}
