<?php

namespace Banot\SDK\Tests\Model\Reports;

use Banot\SDK\Model\Reports\Report;
use Banot\SDK\Model\Reports\ReportCollection;
use PHPUnit\Framework\TestCase;

class ReportCollectionTest extends TestCase
{

	public function testCreateWithAllFields()
	{
		$response = <<<JSON
			{
				"total": 2,
				"data": [
					{
						"name": "dangerous-duck-749",
						"email": "email@email.cz",
						"query": "\"some Lucene query\"",
						"frequency": 24,
						"created": "2020-05-25T09:45:00+0000",
						"lastNotified": null
					},
					{
						"name": "dangerous-duck-749",
						"email": "email@email.cz",
						"query": "\"some Lucene query\"",
						"frequency": 24,
						"created": "2020-05-25T09:45:00+0000",
						"lastNotified": null
					}
				]
			}
			JSON;

		$search = ReportCollection::create(json_decode($response, true));

		$this->assertSame($search->getTotal(), 2);

		$this->assertContainsOnlyInstancesOf(Report::class, $search->getData());
		$this->assertCount(2, $search->getData());
	}

}
