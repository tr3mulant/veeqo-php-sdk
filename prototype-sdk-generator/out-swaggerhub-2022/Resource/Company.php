<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Company\UpdateCompanyDetail;
use IronGate\Veeqo\Requests\Company\ViewCompanyDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Company extends BaseResource
{
	public function viewCompanyDetail(): Response
	{
		return $this->connector->send(new ViewCompanyDetail());
	}


	public function updateCompanyDetail(): Response
	{
		return $this->connector->send(new UpdateCompanyDetail());
	}
}
