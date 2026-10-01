<?php

namespace IronGate\Veeqo\Requests\Misc;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Channel Sellable
 *
 * Creates a channel sellable (linked listing) that connects a Veeqo sellable to a channel product
 * listing. Important: The Accept header must be set to application/vnd.api+json . Omitting this
 * results in a 415 “Unsupported Media Type” error.
 */
class CreateChannelSellable extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/api/v2/channel_sellables";
	}


	public function __construct()
	{
	}
}
