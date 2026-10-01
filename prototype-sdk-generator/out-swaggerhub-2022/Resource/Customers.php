<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Customers\CreateCustomer;
use IronGate\Veeqo\Requests\Customers\ListAllCustomers;
use IronGate\Veeqo\Requests\Customers\UpdateCustomerDetail;
use IronGate\Veeqo\Requests\Customers\ViewCustomerDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Customers extends BaseResource
{
	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 * @param string $query Free text search
	 */
	public function listAllCustomers(?int $pageSize = null, ?int $page = null, ?string $query = null): Response
	{
		return $this->connector->send(new ListAllCustomers($pageSize, $page, $query));
	}


	public function createCustomer(): Response
	{
		return $this->connector->send(new CreateCustomer());
	}


	/**
	 * @param int $id
	 */
	public function viewCustomerDetail(int $id): Response
	{
		return $this->connector->send(new ViewCustomerDetail($id));
	}


	/**
	 * @param int $id
	 */
	public function updateCustomerDetail(int $id): Response
	{
		return $this->connector->send(new UpdateCustomerDetail($id));
	}
}
