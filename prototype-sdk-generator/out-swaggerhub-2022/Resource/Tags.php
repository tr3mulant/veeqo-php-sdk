<?php

namespace IronGate\Veeqo\Resource;

use IronGate\Veeqo\Requests\Tags\CreateNewTag;
use IronGate\Veeqo\Requests\Tags\DeleteTag;
use IronGate\Veeqo\Requests\Tags\ListAllTags;
use IronGate\Veeqo\Requests\Tags\ViewTagDetail;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Tags extends BaseResource
{
	public function listAllTags(): Response
	{
		return $this->connector->send(new ListAllTags());
	}


	public function createNewTag(): Response
	{
		return $this->connector->send(new CreateNewTag());
	}


	/**
	 * @param int $tagId ID of the Tag
	 */
	public function viewTagDetail(int $tagId): Response
	{
		return $this->connector->send(new ViewTagDetail($tagId));
	}


	/**
	 * @param int $tagId ID of the Tag
	 */
	public function deleteTag(int $tagId): Response
	{
		return $this->connector->send(new DeleteTag($tagId));
	}
}
