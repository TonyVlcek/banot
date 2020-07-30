<?php

namespace Banot\SDK\Tests\Model\WebResources;

use Banot\SDK\Model\WebResources\Instruction;
use PHPUnit\Framework\TestCase;

class InstructionTest extends TestCase
{

	public function testCreateWithAllFields()
	{
		$response = <<<'JSON'
{
	"name": "title",
	"type": "attr",
	"selector": "#selector",
	"attribute": "data-title",
	"modifier": "trim"
}
JSON;

		$instruction = Instruction::create(json_decode($response, true));

		$this->assertSame('title', $instruction->getName());
		$this->assertSame('attr', $instruction->getType());
		$this->assertSame('#selector', $instruction->getSelector());
		$this->assertSame('data-title', $instruction->getAttribute());
		$this->assertSame('trim', $instruction->getModifier());
	}

}
