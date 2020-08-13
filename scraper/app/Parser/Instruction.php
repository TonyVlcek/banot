<?php

declare(strict_types=1);

namespace Banot\Scraper\Parser;

use Banot\SDK\Model\WebResources\Instruction as SDKInstruction;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Instruction
{
	public const TYPE_ATTR = 'attr';
	public const TYPE_ATTRS = 'attrs';
	public const TYPE_LINK = 'link';
	public const TYPE_LINKS = 'links';
	public const TYPE_TEXT = 'text';
	public const TYPE_TEXTS = 'texts';

	public string $name;

	public string $type;

	public string $selector;

	public ?string $attribute = null;

	//TODO: Modifiers


	/**
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $name, string $type, string $selector, ?string $attribute = null)
	{
		Assert::oneOf(
			$type, [self::TYPE_ATTR, self::TYPE_ATTRS, self::TYPE_LINK, self::TYPE_LINKS, self::TYPE_TEXT,
			self::TYPE_TEXTS]
		);
		Assert::false(
			(($type === self::TYPE_ATTR || $type === self::TYPE_ATTRS) && ($attribute === null)),
			'When extracting by attribute(s) the "attribute" argument must be specified.'
		);

		$this->name = $name;
		$this->type = $type;
		$this->selector = $selector;
		$this->attribute = $attribute;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public static function from(SDKInstruction $instruction): self
	{
		return new self(
			$instruction->getName(),
			$instruction->getType(),
			$instruction->getSelector(),
			$instruction->getAttribute(),
		);
	}
}
