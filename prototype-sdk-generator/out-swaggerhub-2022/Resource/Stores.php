<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Stores\CreateStore;
use IronGate\Veeqo\Requests\Stores\DeleteChannel;
use IronGate\Veeqo\Requests\Stores\ListAllStores;
use IronGate\Veeqo\Requests\Stores\UpdateStoreDetail;
use IronGate\Veeqo\Requests\Stores\ViewStoreDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Stores extends BaseResource
{
	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 */
	public function listAllStores(?int $pageSize = null, ?int $page = null): Response
	{
		return $this->connector->send(new ListAllStores($pageSize, $page));
	}


	public function createStore(): Response
	{
		return $this->connector->send(new CreateStore());
	}


	/**
	 * @param int $id
	 */
	public function viewStoreDetail(int $id): Response
	{
		return $this->connector->send(new ViewStoreDetail($id));
	}


	/**
	 * @param int $id
	 */
	public function updateStoreDetail(int $id): Response
	{
		return $this->connector->send(new UpdateStoreDetail($id));
	}


	/**
	 * @param int $id
	 */
	public function deleteChannel(int $id): Response
	{
		return $this->connector->send(new DeleteChannel($id));
	}
}
