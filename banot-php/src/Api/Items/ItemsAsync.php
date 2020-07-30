<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api\Items;

use Banot\SDK\Api\HttpApiAsync;
use Banot\SDK\Exceptions\RuntimeException;
use Banot\SDK\Model\EmptyResponse;
use Banot\SDK\Model\Items\Item;
use Banot\SDK\Model\Items\ItemsNeedScrapingResponse;
use Banot\SDK\Model\Items\SearchResponse;
use React\Promise\PromiseInterface;


class ItemsAsync extends HttpApiAsync implements IItems
{

	/**
	 * @return PromiseInterface<SearchResponse, RuntimeException>
	 */
	public function search(string $query, ?int $size = null, ?int $from = null): PromiseInterface
	{
		$params = array_filter(
			[
				'query' => $query,
				'from' => $from,
				'size' => $size,
			],
			fn ($param) => $param !== null
		);

		return $this->get('/items', $params, SearchResponse::class);
	}

	/**
	 * @return PromiseInterface<ItemsNeedScrapingResponse, RuntimeException>
	 */
	public function needScraping(array $itemUrls): PromiseInterface
	{
		return $this->post('/items/need-scraping', $itemUrls, ItemsNeedScrapingResponse::class);
	}

	/**
	 * @return PromiseInterface<Item, RuntimeException>
	 */
	public function addItem(Item $item): PromiseInterface
	{
		return $this->put('/items', $item->toArray(), EmptyResponse::class);
	}

}
