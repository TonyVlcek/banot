<?php


declare(strict_types=1);

namespace App\Api\Responses;

use App\Orm\Instructions\Instruction;
use Nextras\Orm\Entity\IEntity;

final class InstructionRes implements SerializableResponse, FromOrmEntityResponse
{
	public int $id;
	public string $name;
	public string $target;
	public string $type;
	public string $selector;
	public ?string $attribute;
	public ?string $modifier;

	/**
	 * @param IEntity|Instruction $rootPage
	 */
	public static function fromOrmEntity(IEntity $rootPage): self
	{
		$self = new self();
		$self->id = $rootPage->id;
		$self->name = $rootPage->name;
		$self->target = $rootPage->target;
		$self->type = $rootPage->type;
		$self->selector = $rootPage->selector;
		$self->attribute = $rootPage->attribute;
		$self->modifier = $rootPage->modifier;

		return $self;
	}

}
