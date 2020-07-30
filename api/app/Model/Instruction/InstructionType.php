<?php


declare(strict_types=1);

namespace App\Model\Instruction;

use MyCLabs\Enum\Enum;

/**
 * @method static InstructionType ATTR()
 * @method static InstructionType ATTRS()
 * @method static InstructionType LINK()
 * @method static InstructionType LINKS()
 * @method static InstructionType TEXT()
 * @method static InstructionType TEXTS()
 */
final class InstructionType extends Enum
{
	private const ATTR = 'attr';
	private const ATTRS = 'attrs';
	private const LINK = 'link';
	private const LINKS = 'links';
	private const TEXT = 'text';
	private const TEXTS = 'texts';
}
