<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

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
