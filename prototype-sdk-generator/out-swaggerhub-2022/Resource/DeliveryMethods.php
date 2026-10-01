<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\DeliveryMethods\CreateDeliveryMethod;
use IronGate\Veeqo\Requests\DeliveryMethods\DeleteDeliveryMethod;
use IronGate\Veeqo\Requests\DeliveryMethods\ListAllDeliveryMethods;
use IronGate\Veeqo\Requests\DeliveryMethods\UpdateDeliveryMethodDetail;
use IronGate\Veeqo\Requests\DeliveryMethods\ViewDeliveryMethodDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class DeliveryMethods extends BaseResource
{
	/**
	 * @param int $pageSize Amount of results
	 * @param int $page Page to show
	 */
	public function listAllDeliveryMethods(?int $pageSize = null, ?int $page = null): Response
	{
		return $this->connector->send(new ListAllDeliveryMethods($pageSize, $page));
	}


	public function createDeliveryMethod(): Response
	{
		return $this->connector->send(new CreateDeliveryMethod());
	}


	/**
	 * @param int $id
	 */
	public function viewDeliveryMethodDetail(int $id): Response
	{
		return $this->connector->send(new ViewDeliveryMethodDetail($id));
	}


	/**
	 * @param int $id
	 */
	public function updateDeliveryMethodDetail(int $id): Response
	{
		return $this->connector->send(new UpdateDeliveryMethodDetail($id));
	}


	/**
	 * @param int $id
	 */
	public function deleteDeliveryMethod(int $id): Response
	{
		return $this->connector->send(new DeleteDeliveryMethod($id));
	}
}
