<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class DeliveryMethod extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public int|float|null $cost = null,
		public ?string $name = null,
		#[MapName('user_id')]
		public int|float|null $userId = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('deleted_at')]
		public ?string $deletedAt = null,
		#[MapName('deleted_by_id')]
		public int|float|null $deletedById = null,
	) {
	}
}
