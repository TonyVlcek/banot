<?php


declare(strict_types=1);

namespace App\Api\Responses;

use Nextras\Orm\Entity\IEntity;

interface FromOrmEntityResponse
{
	public static function fromOrmEntity(IEntity $entity): self;
}
