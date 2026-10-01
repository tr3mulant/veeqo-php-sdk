<?php

namespace IronGate\Veeqo;

use IronGate\Veeqo\Resource\Misc;
use Saloon\Http\Connector;

/**
 * Veeqo API (reconstructed from developers.veeqo.com)
 */
class Veeqo extends Connector
{
	public function __construct()
	{
	}


	public function resolveBaseUrl(): string
	{
		return "https://api.veeqo.com";
	}


	public function misc(): Misc
	{
		return new Misc($this);
	}
}
