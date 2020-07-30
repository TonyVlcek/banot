<?php

namespace Banot\SDK\Tests\Model\Items;

use Banot\SDK\Model\Items\Item;
use Banot\SDK\Model\Items\SearchResponse;
use PHPUnit\Framework\TestCase;

class SearchResponseTest extends TestCase
{

	public function testCreateWithAllFields()
	{
		$response = <<<JSON
			{
				"total": 3,
				"data": [
					{"url": "https://url-1.com", "title": "title 1", "description": "description 1"},
					{"url": "https://url-1.com", "title": "title 1", "description": "description 1"},
					{"url": "https://url-1.com", "title": "title 1", "description": "description 1"}
				]
			}
			JSON;

		$search = SearchResponse::create(json_decode($response, true));

		$this->assertSame($search->getTotal(), 3);

		$this->assertContainsOnlyInstancesOf(Item::class, $search->getItems());
		$this->assertCount(3, $search->getItems());
	}

}
