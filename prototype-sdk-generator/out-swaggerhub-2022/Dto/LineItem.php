<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class LineItem extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		#[MapName('price_per_unit')]
		public int|float|null $pricePerUnit = null,
		public int|float|null $quantity = null,
		#[MapName('tax_rate')]
		public int|float|null $taxRate = null,
		#[MapName('taxless_discount_per_unit')]
		public int|float|null $taxlessDiscountPerUnit = null,
		#[MapName('additional_options')]
		public ?string $additionalOptions = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('remote_id')]
		public int|float|null $remoteId = null,
		public ?Sellable $sellable = null,
	) {
	}
}
