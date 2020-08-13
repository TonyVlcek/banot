<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Controllers\V1\Items;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Requests\ItemRequest;
use App\Api\Responses\ItemSavedRes;
use App\Facades\ItemFacade;
use InvalidArgumentException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Path("/items")
 * @Tag(name="items")
 */
class PutItemController extends BaseV1Controller
{
	/** @inject */
	public ItemFacade $itemFacade;

	/** @inject */
	public SerializerInterface $serializer;


	/**
	 * @Path("/")
	 * @Method("PUT")
	 * @RequestBody(entity="App\Api\Requests\ItemRequest")
	 */
	public function createOrUpdate(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var ItemRequest $item */
		$item = $request->getEntity();

		$foundItem = $this->itemFacade->getByUrl($item->url);

		if ($foundItem === null) {
			return $this->createNew($item, $response);
		} else {
			return $this->updateExisting($foundItem->getId(), $item, $response);
		}
	}

	private function createNew(ItemRequest $item, ApiResponse $response): ApiResponse
	{
		try {
			$itemId = $this->itemFacade->createItem($item->toArray());
			$result = new ItemSavedRes($itemId, $item->url);

			return $response->withStatus(ApiResponse::S201_CREATED)
				->withHeader('Content-Type', 'application/json')
				->writeBody($this->serializer->serialize($result, 'json'));
		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage($e->getMessage());
		}
	}

	private function updateExisting(string $itemId, ItemRequest $item, ApiResponse $response): ApiResponse
	{
		try {
			$version = $this->itemFacade->updateItem($itemId, $item->toArray());
			$result = new ItemSavedRes($itemId, $item->url, $version);

			return $response->withStatus(ApiResponse::S200_OK)
				->withHeader('Content-Type', 'application/json')
				->writeBody($this->serializer->serialize($result, 'json'));
		} catch (InvalidArgumentException $e) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S400_BAD_REQUEST)
				->withMessage($e->getMessage());
		}
	}
}
