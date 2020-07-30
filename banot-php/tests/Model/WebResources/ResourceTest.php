<?php

namespace Banot\SDK\Tests\Model\WebResources;

use Banot\SDK\Model\WebResources\Instruction;
use Banot\SDK\Model\WebResources\WebResource;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{

	public function testCreateWithAllFields()
	{
		$response = <<<'JSON'
{
	"name": "Test Resource",
	"instructions": {
		"listPage": [
			{
				"name": "nextUrl",
				"type": "link",
				"selector": "#snippet-vp-paginator a:last-child"
			},
			{
				"name": "detailsUrls",
				"type": "links",
				"selector": "a.offer__title"
			}
		],
		"detailPage": [
			{
				"name": "title",
				"type": "text",
				"selector": ".page-header > h1"
			},
			{
				"name": "description",
				"type": "text",
				"selector": ".detail__desc"
			},
			{
				"name": "price",
				"type": "text",
				"selector": "div.item__detail > span.price__raw",
				"modifier": "stringToNumber"
			}
		]
	},
	"rootPages": [
		"https://root-page-1.com",
		"https://root-page-2.com"
	]
}
JSON;

		$resource = WebResource::create(json_decode($response, true));

		$this->assertSame('Test Resource', $resource->getName());
		$this->assertSame(['https://root-page-1.com', 'https://root-page-2.com'], $resource->getRootPages());

		$this->assertContainsOnlyInstancesOf(Instruction::class, $resource->getListInstructions());
		$this->assertCount(2, $resource->getListInstructions());

		$this->assertContainsOnlyInstancesOf(Instruction::class, $resource->getDetailInstructions());
		$this->assertCount(3, $resource->getDetailInstructions());
	}

}
