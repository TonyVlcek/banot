<?php

namespace Banot\SDK\Tests\Model\Reports;

use Banot\SDK\Model\Reports\Report;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{

	public function testCreateWithAllFields()
	{
		$response = <<<'JSON'
			{
				"name": "dangerous-duck-749",
				"email": "email@email.cz",
				"query": "\"some Lucene query\"",
				"frequency": 24,
				"created": "2020-05-25T09:45:00+0000",
				"lastNotified": null
			}
		JSON;

		$report = Report::create(json_decode($response, true));

		$this->assertSame($report->getName(), 'dangerous-duck-749');
		$this->assertSame($report->getEmail(), 'email@email.cz');
		$this->assertSame($report->getQuery(), '"some Lucene query"');
		$this->assertSame($report->getFrequency(), 24);
		$this->assertEquals($report->getCreated(), new DateTimeImmutable('2020-05-25T09:45:00+0000'));
		$this->assertNull($report->getLastNotified());
	}

}
