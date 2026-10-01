<?php

namespace IronGate\Veeqo\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

class Company extends SpatieData
{
	public function __construct(
		public int|float|null $id = null,
		public ?string $name = null,
		#[MapName('created_at')]
		public ?string $createdAt = null,
		#[MapName('updated_at')]
		public ?string $updatedAt = null,
		#[MapName('has_right_to_use_veeqo')]
		public ?bool $hasRightToUseVeeqo = null,
		#[MapName('billing_period_started')]
		public ?bool $billingPeriodStarted = null,
		#[MapName('chargify_product_handle')]
		public ?string $chargifyProductHandle = null,
		#[MapName('chargify_current_plan')]
		public ?string $chargifyCurrentPlan = null,
		#[MapName('trial_end_date')]
		public ?string $trialEndDate = null,
		#[MapName('active_features')]
		public ?array $activeFeatures = null,
		#[MapName('veeqo_product_name')]
		public ?string $veeqoProductName = null,
		#[MapName('has_ever_created_remote_channel')]
		public ?bool $hasEverCreatedRemoteChannel = null,
		public ?object $owner = null,
		#[MapName('printnode_api_key')]
		public ?string $printnodeApiKey = null,
		public ?array $employees = null,
		#[MapName('trialing?')]
		public ?bool $trialing = null,
		public ?bool $paying = null,
		#[MapName('shopify_app_store_referral?')]
		public ?bool $shopifyAppStoreReferral = null,
		public ?object $settings = null,
		#[MapName('plan_features')]
		public ?object $planFeatures = null,
	) {
	}
}
