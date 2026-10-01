<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Product extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $title = null,
		#[MapName('created_by_id')]
		public int|float|null $createdById = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		public int|float|null $weight = null,
		#[MapName('origin_country')]
		public ?string $originCountry = null,
		#[MapName('deleted_at')]
		public ?string $deletedAt = null,
		#[MapName('deleted_by_id')]
		public int|float|null $deletedById = null,
		#[MapName('hs_tariff_number')]
		public ?string $hsTariffNumber = null,
		public ?string $notes = null,
		#[MapName('product_tax_rate_id')]
		public int|float|null $productTaxRateId = null,
		#[MapName('tax_rate')]
		public int|float|null $taxRate = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('updated_by_id')]
		public int|float|null $updatedById = null,
		#[MapName('web_meta_description')]
		public ?string $webMetaDescription = null,
		#[MapName('web_meta_keywords')]
		public ?string $webMetaKeywords = null,
		#[MapName('web_meta_title')]
		public ?string $webMetaTitle = null,
		#[MapName('web_page_title')]
		public ?string $webPageTitle = null,
		#[MapName('web_page_url')]
		public ?string $webPageUrl = null,
		#[MapName('estimated_delivery')]
		public ?string $estimatedDelivery = null,
		#[MapName('total_quantity_sold')]
		public int|float|null $totalQuantitySold = null,
		#[MapName('requires_review')]
		public ?bool $requiresReview = null,
		public ?string $image = null,
		public ?string $brand = null,
		public ?array $sellables = null,
		#[MapName('channel_products')]
		public ?array $channelProducts = null,
		#[MapName('active_channels')]
		public ?array $activeChannels = null,
		public ?array $tags = null,
		#[MapName('thumbnail_url')]
		public ?string $thumbnailUrl = null,
		public ?string $description = null,
		#[MapName('on_hand_value')]
		public int|float|null $onHandValue = null,
		#[MapName('main_image_src')]
		public ?string $mainImageSrc = null,
		#[MapName('total_allocated_stock_level')]
		public int|float|null $totalAllocatedStockLevel = null,
		#[MapName('total_available_stock_level')]
		public int|float|null $totalAvailableStockLevel = null,
		#[MapName('total_stock_level')]
		public int|float|null $totalStockLevel = null,
		public ?object $inventory = null,
	) {
	}
}
