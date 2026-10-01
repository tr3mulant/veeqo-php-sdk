<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\BulkTagging\TaggingProducts;
use IronGate\Veeqo\Requests\BulkTagging\UntaggingOrders;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class BulkTagging extends BaseResource
{
	public function taggingProducts(): Response
	{
		return $this->connector->send(new TaggingProducts());
	}


	public function untaggingOrders(): Response
	{
		return $this->connector->send(new UntaggingOrders());
	}
}
