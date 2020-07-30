<?php


declare(strict_types=1);

namespace App\Model\Instruction;

use MyCLabs\Enum\Enum;

/**
 * @method static InstructionTargetPage LIST()
 * @method static InstructionTargetPage DETAIL()
 */
final class InstructionTargetPage extends Enum
{
	private const LIST = 'list';
	private const DETAIL = 'detail';
}
