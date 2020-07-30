<?php

namespace Banot\SDK\Tests\Model\Items;

use Banot\SDK\Model\Items\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{

	public function testCreateWithAllFields()
	{
		$response = <<<'JSON'
{
	"url": "https://test-item-url.com/1234",
	"title": "Test Item",
	"description": "Test Description",
	"price": "1000"
}
JSON;

		$item = Item::create(json_decode($response, true));

		$this->assertSame($item->getUrl(), 'https://test-item-url.com/1234');
		$this->assertSame($item->getTitle(), 'Test Item');
		$this->assertSame($item->getDescription(), 'Test Description');
		$this->assertSame($item->getPrice(), '1000');
	}

	public function testCreateWithMinimalFields()
	{
		$response = <<<'JSON'
{
	"url": "https://test-item-url.com/1234"
}
JSON;

		$item = Item::create(json_decode($response, true));

		$this->assertSame($item->getUrl(), 'https://test-item-url.com/1234');
		$this->assertSame($item->getTitle(), null);
		$this->assertSame($item->getDescription(), null);
		$this->assertSame($item->getPrice(), null);
	}

}
