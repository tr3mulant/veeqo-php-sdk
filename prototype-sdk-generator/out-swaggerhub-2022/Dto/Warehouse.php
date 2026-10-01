<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Warehouse extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $name = null,
		#[MapName('display_position')]
		public int|float|null $displayPosition = null,
	) {
	}
}
