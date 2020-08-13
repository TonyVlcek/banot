<?php

declare(strict_types=1);

namespace Banot\Scraper\Tests;

use Banot\Scraper\Parser;
use Banot\SDK\Model\WebResources;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InstructionTest extends TestCase
{
	/** @test */
	public function testMissingAttr(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage(
			'When extracting by attribute(s) the "attribute" argument must be specified.'
		);

		new Parser\Instruction('name', Parser\Instruction::TYPE_ATTR, 'selector');
	}

	/** @test */
	public function testInvalidType(): void
	{
		$this->expectException(InvalidArgumentException::class);

		new Parser\Instruction('name', 'some-non-existent-type', 'selector');
	}

	/** @test */
	public function testFromObject(): void
	{
		$data['name'] = 'name';
		$data['target'] = 'detail';
		$data['type'] = Parser\Instruction::TYPE_ATTR;
		$data['selector'] = 'selector';
		$data['attribute'] = 'attr';

		$sdkInstruction = WebResources\Instruction::create($data);

		$i1 = new Parser\Instruction($data['name'], $data['type'], $data['selector'], $data['attribute']);
		$i2 = Parser\Instruction::from($sdkInstruction);

		$this->assertEquals($i1, $i2);
	}
}
