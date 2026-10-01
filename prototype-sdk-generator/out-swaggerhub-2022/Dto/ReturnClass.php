<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class ReturnClass extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $number = null,
		public ?string $status = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		public ?string $type = null,
		#[MapName('line_items')]
		public ?array $lineItems = null,
		public ?object $user = null,
		#[MapName('warehouse_name')]
		public ?string $warehouseName = null,
	) {
	}
}
