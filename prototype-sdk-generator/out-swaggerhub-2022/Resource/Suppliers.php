<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Suppliers\CreateNewSupplier;
use IronGate\Veeqo\Requests\Suppliers\DeleteSupplier;
use IronGate\Veeqo\Requests\Suppliers\ListAllSuppliers;
use IronGate\Veeqo\Requests\Suppliers\UpdateSupplierDetail;
use IronGate\Veeqo\Requests\Suppliers\ViewSupplierDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Suppliers extends BaseResource
{
	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 */
	public function listAllSuppliers(?int $pageSize = null, ?int $page = null): Response
	{
		return $this->connector->send(new ListAllSuppliers($pageSize, $page));
	}


	public function createNewSupplier(): Response
	{
		return $this->connector->send(new CreateNewSupplier());
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function viewSupplierDetail(int $id): Response
	{
		return $this->connector->send(new ViewSupplierDetail($id));
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function updateSupplierDetail(int $id): Response
	{
		return $this->connector->send(new UpdateSupplierDetail($id));
	}


	/**
	 * @param int $id ID of the Supplier
	 */
	public function deleteSupplier(int $id): Response
	{
		return $this->connector->send(new DeleteSupplier($id));
	}
}
