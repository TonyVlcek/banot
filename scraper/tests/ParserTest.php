<?php

declare(strict_types=1);

namespace Banot\Scraper\Tests;

use Banot\SDK\Model\WebResources;
use Banot\Scraper\Parser\Parser;
use Banot\Scraper\Parser\Instruction;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /** @test */
    public function testParseAttr(): void
    {
        $valueToBeExtracted = 'value';

        $html = "<span data-value='{$valueToBeExtracted}'></span>";

        $instruction = WebResources\Instruction::create([
            'target' => 'detail',
            'name' => 'name',
            'type' => Instruction::TYPE_ATTR,
            'selector' => 'span',
            'attribute' => 'data-value'
        ]);

        $parser = new Parser([$instruction]);

        $this->assertSame($parser->parse($html, '')['name'], $valueToBeExtracted);
    }

    /** @test */
    public function testParseAttrs(): void
    {
        $valuesToBeExtracted = ['one', 'two', 'three'];

        $html = "<span data-value='{$valuesToBeExtracted[0]}'></span>";
        $html .= "<span data-value='{$valuesToBeExtracted[1]}'></span>";
        $html .= "<span data-value='{$valuesToBeExtracted[2]}'></span>";

        $instruction = WebResources\Instruction::create([
            'target' => 'detail',
            'name' => 'name',
            'type' => Instruction::TYPE_ATTRS,
            'selector' => 'span',
            'attribute' => 'data-value'
        ]);
        $parser = new Parser([$instruction]);

        $this->assertSame($parser->parse($html, '')['name'], $valuesToBeExtracted);
    }

    /** @test */
    public function testParseLink(): void
    {
        $baseUrl = 'https://base.com';
        $link = '/some/url/123.html';
        $html = "<a href='{$link}'>Link</a>";

        $instruction = WebResources\Instruction::create([
            'target' => 'detail',
            'name' => 'name',
            'type' => Instruction::TYPE_LINK,
            'selector' => 'a',
        ]);
        $parser = new Parser([$instruction]);

        $this->assertSame($parser->parse($html, $baseUrl), ['name' => $baseUrl.$link]);
    }

    /** @test */
    public function testParseLinks(): void
    {
        $baseUrl = 'https://base.com';
        $links = ['/some/url/1.html', '/some/url/2.html', '/some/url/3.html'];
        $html = "<a href='{$links[0]}'>Link</a>";
        $html .= "<a href='{$links[1]}'>Link</a>";
        $html .= "<a href='{$links[2]}'>Link</a>";

        $instruction = WebResources\Instruction::create([
            'target' => 'detail',
            'name' => 'name',
            'type' => Instruction::TYPE_LINKS,
            'selector' => 'a',
        ]);
        $parser = new Parser([$instruction]);

        $this->assertSame(
            $parser->parse($html, $baseUrl),
            ['name' => [$baseUrl.$links[0], $baseUrl.$links[1], $baseUrl.$links[2]]]
        );
    }

    /** @test */
    public function testParseText(): void
    {
        $html = "<span>Non sed <b>quisquam</b> ut labore.</span>";

        $instruction = WebResources\Instruction::create([
            'target' => 'detail',
            'name' => 'name',
            'type' => Instruction::TYPE_TEXT,
            'selector' => 'span',
        ]);
        $parser = new Parser([$instruction]);

        $this->assertSame($parser->parse($html, ''), ['name' => 'Non sed quisquam ut labore.']);
    }

    /** @test */
    public function testParseTexts(): void
    {
        $html = "<span>Non sed <b>quisquam</b> ut labore.</span>";
        $html .= "<span>Non sed <b>quisquam</b> ut labore.</span>";
        $html .= "<span>Non sed <b>quisquam</b> ut labore.</span>";

        $instruction = WebResources\Instruction::create([
            'target' => 'detail',
            'name' => 'name',
            'type' => Instruction::TYPE_TEXTS,
            'selector' => 'span',
        ]);
        $parser = new Parser([$instruction]);

        $this->assertSame(
            $parser->parse($html, ''),
            ['name' =>['Non sed quisquam ut labore.', 'Non sed quisquam ut labore.', 'Non sed quisquam ut labore.']]
        );
    }
}
