<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class StockEntry extends SpatieData
{
	public function __construct(
		#[MapName('sellable_id')]
		public int|float|null $sellableId = null,
		#[MapName('warehouse_id')]
		public int|float|null $warehouseId = null,
		public ?bool $infinite = null,
		#[MapName('allocated_stock_level')]
		public int|float|null $allocatedStockLevel = null,
		#[MapName('stock_running_low')]
		public ?bool $stockRunningLow = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('incoming_stock_level')]
		public int|float|null $incomingStockLevel = null,
		#[MapName('transit_outgoing_stock_level')]
		public int|float|null $transitOutgoingStockLevel = null,
		public ?object $warehouse = null,
		#[MapName('physical_stock_level')]
		public int|float|null $physicalStockLevel = null,
		#[MapName('available_stock_level')]
		public int|float|null $availableStockLevel = null,
		#[MapName('sellable_on_hand_value')]
		public int|float|null $sellableOnHandValue = null,
		#[MapName('transit_incoming_stock_level')]
		public int|float|null $transitIncomingStockLevel = null,
		public ?string $location = null,
	) {
	}
}
