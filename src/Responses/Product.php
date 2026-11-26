<?php

namespace TremulantTech\Veeqo\Responses;

use DateTimeImmutable;

readonly class Product
{
    /**
     * @param  list<ChannelSummary>  $activeChannels
     * @param  list<ChannelProduct>  $channelProducts
     * @param  numeric-string  $onHandValue
     * @param  list<Sellable>  $sellables
     */
    public function __construct(
        public int $id,
        public array $activeChannels,
        public array $channelProducts,
        public DateTimeImmutable $createdAt,
        public int $createdById,
        public string $description,
        public ProductInventory $inventory,
        public string $notes,
        public string $onHandValue,
        public array $sellables,
        public float $taxRate,
        public string $title,
        public int $totalAllocatedStockLevel,
        public int $totalAvailableStockLevel,
        public int $totalQuantitySold,
        public int $totalStockLevel,
        public DateTimeImmutable $updatedAt,
        public string $webMetaDescription,
        public string $webMetaKeywords,
        public string $webMetaTitle,
        public string $webPageTitle,
        public string $webPageUrl,
        public float $weight,
        public ?Brand $brand = null,
        public ?int $estimatedDelivery = null,
        public ?bool $hazmat = null,
        public ?string $hsTariffNumber = null,
        public ?string $image = null,
        public ?Image $mainImage = null,
        public ?string $mainImageSrc = null,
        public ?string $originCountry = null,
        public ?bool $requiresReview = null,
        public ?string $thumbnailUrl = null,
        public ?int $updatedById = null,
    ) {}
}
