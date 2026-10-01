<?php

namespace IronGate\Veeqo\Requests\Tags;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List All Tags
 */
class ListAllTags extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/tags";
	}


	public function __construct()
	{
	}
}
