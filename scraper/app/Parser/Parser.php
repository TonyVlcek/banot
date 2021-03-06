<?php

declare(strict_types=1);

namespace Banot\Scraper\Parser;

use Banot\SDK\Model\WebResources\Instruction as SDKInstruction;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link;

class Parser
{
	private array $instructions;


	/**
	 * Validates the instructions passed in, @param SDKInstruction[]
	 *
	 * @throws InvalidArgumentException when instructions not valid
	 * @see Instruction for details.
	 *
	 */
	public function __construct(array $instructions)
	{
		$this->instructions = array_map(fn($instruction) => Instruction::from($instruction), $instructions);
	}

	/**
	 * Crawls the passed in HTML and extracts values based on Instructions. The extracted values are returned
	 * in an associative array, key is always the name of the instruction.
	 */
	public function parse(string $html, string $uri): array
	{
		$crawler = new Crawler($html, $uri);
		$results = [];

		/** @var Instruction $ins */
		foreach ($this->instructions as $ins) {

			$nodes = $crawler->filter($ins->selector);

			if ($nodes->count() === 0) {
				$results[$ins->name] = null;
				continue;
			}

			$values = null;

			switch ($ins->type) {
				case Instruction::TYPE_ATTR:
					$values = $nodes->attr($ins->attribute);
					break;

				case Instruction::TYPE_ATTRS:
					$values = $nodes->extract([$ins->attribute]);
					break;

				case Instruction::TYPE_LINK:
					$values = $nodes->link()->getUri();
					break;

				case Instruction::TYPE_LINKS:
					$values = array_map(fn(Link $link) => $link->getUri(), $nodes->links());
					break;

				case Instruction::TYPE_TEXT:
					$values = $nodes->text();
					break;

				case Instruction::TYPE_TEXTS:
					$values = $nodes->extract(['_text']);
					break;

				default:
					throw new \LogicException("Instruction '{$ins->type}' is not supported.");
			}

			// TODO: Implement modifiers

			$results[$ins->name] = $values;
		}

		return $results;
	}
}
