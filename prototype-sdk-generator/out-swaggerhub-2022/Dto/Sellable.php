<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Sellable extends SpatieData
{
	public function __construct(
		#[MapName('allocated_stock_level_at_all_warehouses')]
		public int|float|null $allocatedStockLevelAtAllWarehouses = null,
		public int|float|null $id = null,
		public ?string $type = null,
		public ?string $title = null,
		#[MapName('sku_code')]
		public ?string $skuCode = null,
		#[MapName('upc_code')]
		public ?string $upcCode = null,
		#[MapName('model_number')]
		public ?string $modelNumber = null,
		public int|float|null $price = null,
		#[MapName('cost_price')]
		public int|float|null $costPrice = null,
		#[MapName('min_reorder_level')]
		public int|float|null $minReorderLevel = null,
		#[MapName('quantity_to_reorder')]
		public int|float|null $quantityToReorder = null,
		#[MapName('created_by_id')]
		public int|float|null $createdById = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('weight_grams')]
		public int|float|null $weightGrams = null,
		#[MapName('weight_unit')]
		public ?string $weightUnit = null,
		#[MapName('product_title')]
		public ?string $productTitle = null,
		#[MapName('full_title')]
		public ?string $fullTitle = null,
		#[MapName('sellable_title')]
		public ?string $sellableTitle = null,
		public int|float|null $profit = null,
		public int|float|null $margin = null,
		#[MapName('tax_rate')]
		public int|float|null $taxRate = null,
		#[MapName('estimated_delivery')]
		public ?string $estimatedDelivery = null,
		#[MapName('origin_country')]
		public ?string $originCountry = null,
		#[MapName('hs_tariff_number')]
		public ?string $hsTariffNumber = null,
		public ?object $product = null,
		public ?array $reorders = null,
		#[MapName('stock_entries')]
		public ?array $stockEntries = null,
		#[MapName('variant_option_specifics')]
		public ?array $variantOptionSpecifics = null,
		#[MapName('variant_property_specifics')]
		public ?array $variantPropertySpecifics = null,
		public ?array $images = null,
		#[MapName('measurement_attributes')]
		public ?object $measurementAttributes = null,
		#[MapName('main_thumbnail_url')]
		public ?string $mainThumbnailUrl = null,
		#[MapName('available_stock_level_at_all_warehouses')]
		public int|float|null $availableStockLevelAtAllWarehouses = null,
		#[MapName('stock_level_at_all_warehouses')]
		public int|float|null $stockLevelAtAllWarehouses = null,
		public ?object $inventory = null,
		public int|float|null $weight = null,
	) {
	}
}
