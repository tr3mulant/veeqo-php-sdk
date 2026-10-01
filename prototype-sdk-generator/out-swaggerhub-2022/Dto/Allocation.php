<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Allocation extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('total_weight')]
		public int|float|null $totalWeight = null,
		#[MapName('weight_unit')]
		public ?string $weightUnit = null,
		#[MapName('allocated_by_id')]
		public int|float|null $allocatedById = null,
		#[MapName('order_id')]
		public int|float|null $orderId = null,
		#[MapName('packed_completely')]
		public ?bool $packedCompletely = null,
		#[MapName('due_date')]
		public ?string $dueDate = null,
		#[MapName('dispatch_date')]
		public ?string $dispatchDate = null,
		#[MapName('line_items')]
		public ?array $lineItems = null,
		#[MapName('recommended_shipping_options')]
		public ?object $recommendedShippingOptions = null,
		public ?Shipment $shipment = null,
		public ?Warehouse $warehouse = null,
	) {
	}
}
