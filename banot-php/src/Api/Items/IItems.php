<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api\Items;

use Banot\SDK\Model\Items\Item;


interface IItems
{

	public function search(string $query, ?int $size = null, ?int $from = null);

	public function needScraping(array $itemUrls);

	public function addItem(Item $item);

}
