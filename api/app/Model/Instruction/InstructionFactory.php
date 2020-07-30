<?php declare(strict_types=1);

namespace App\Model\Instruction;

use App\Api\Requests\InstructionRequest;
use App\Orm\Instructions\Instruction;

class InstructionFactory
{

	public function createFromRequestDTO(InstructionRequest $instruction): Instruction
	{
		$newInstr = new Instruction();
		$newInstr->name = $instruction->name;
		$newInstr->target = $instruction->target->getValue(); //TODO: enum container
		$newInstr->type = $instruction->type->getValue(); //TODO: enum container
		$newInstr->selector = $instruction->selector;
		$newInstr->attribute = $instruction->attribute;
		$newInstr->modifier = $instruction->modifier;

		return $newInstr;
	}

}
