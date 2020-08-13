<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Requests;

use Apitte\Core\Mapping\Request\BasicEntity;
use App\Model\Instruction\InstructionTargetPage;
use App\Model\Instruction\InstructionType;

final class InstructionRequest extends BasicEntity
{
	/** @var string */
	public $name;

	/** @var InstructionTargetPage */
	public $target;

	/** @var InstructionType */
	public $type;

	/** @var string */
	public $selector;

	/** @var string|null */
	public $attribute = null;

	/** @var string|null  */
	public $modifier = null;


	protected function normalize(string $property, $value)
	{
		if ($property === 'target') {
			return new InstructionTargetPage($value);
		}

		if ($property === 'type') {
			return new InstructionType($value);
		}

		//TODO: Validate logic in here? e.g. type attr must have attribute specified

		return parent::normalize($property, $value);
	}
}
