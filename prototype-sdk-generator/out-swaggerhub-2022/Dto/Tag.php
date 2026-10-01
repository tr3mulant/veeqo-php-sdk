<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Tag extends SpatieData
{
	public function __construct(
		public ?int $id = null,
		public ?string $name = null,
		public ?string $colour = null,
		#[MapName('company_id')]
		public ?int $companyId = null,
		#[MapName('taggings_count')]
		public ?int $taggingsCount = null,
		#[MapName('deleted_at')]
		public ?string $deletedAt = null,
		#[MapName('deleted_by_id')]
		public ?int $deletedById = null,
	) {
	}
}
