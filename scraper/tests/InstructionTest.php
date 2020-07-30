<?php

declare(strict_types=1);

namespace Banot\Scraper\Tests;

use Banot\Scraper\Parser\Instruction;
use Banot\SDK\Model\Resources\Instruction as SDKInstruction;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InstructionTest extends TestCase
{
    /** @test */
    public function testMissingAttr(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'When extracting by attribute(s) the $attr argument must be specified.'
        );

        new Instruction('name', Instruction::TYPE_ATTR, 'selector');
    }

    /** @test */
    public function testInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Instruction('name', 'some-non-existent-type', 'selector');
    }

    /** @test */
    public function testFromObject(): void
    {
        $data['name'] = 'name';
        $data['type'] = Instruction::TYPE_ATTR;
        $data['selector'] = 'selector';
        $data['attribute'] = 'attr';

        $sdkInstruction = SDKInstruction::create($data);

        $i1 = new Instruction($data['name'], $data['type'], $data['selector'], $data['attribute']);
        $i2 = Instruction::from($sdkInstruction);

        $this->assertEquals($i1, $i2);
    }
}
