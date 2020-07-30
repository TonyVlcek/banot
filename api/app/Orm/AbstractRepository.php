<?php


declare(strict_types=1);

namespace App\Orm;

use App\Exceptions\EntityNotFoundException;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Repository\Repository;

abstract class AbstractRepository extends Repository
{
	/**
	 * @throws EntityNotFoundException
	 */
	public function getExistingById($id): IEntity
	{
		if ($id instanceof IEntity) {
			return $id;
		}

		$result = $this->getById($id);
		if ($result === null) {
			throw new EntityNotFoundException("Entity id:{$id} not found.");
		}

		return $result;
	}
}
