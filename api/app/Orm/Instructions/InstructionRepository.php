<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Orm\Instructions;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

/**
 * @method ICollection|Instruction[] findAll()
 * @method Instruction|null getById($id)
 */
class InstructionRepository extends Repository
{
	/**
	 * @inheritDoc
	 */
	public static function getEntityClassNames(): array
	{
		return [Instruction::class];
	}
}
