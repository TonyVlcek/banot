<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api\Items;

use Banot\SDK\Api\HttpApiSync;
use Banot\SDK\Model\Items\Item;
use Banot\SDK\Model\Items\ItemsNeedScrapingResponse;
use Banot\SDK\Model\Items\SearchResponse;
use function Clue\React\Block\await;


/**
 * @property ItemsAsync $async
 */
class ItemsSync extends HttpApiSync implements IItems
{

	public function search(string $query, ?int $size = null, ?int $from = null): SearchResponse
	{
		return await($this->async->search($query, $size, $from), $this->loop);
	}

	public function needScraping(array $itemUrls): ItemsNeedScrapingResponse
	{
		return await($this->async->needScraping($itemUrls), $this->loop);
	}

	public function addItem(Item $item): Item
	{
		return await($this->async->addItem($item), $this->loop);
	}
}
