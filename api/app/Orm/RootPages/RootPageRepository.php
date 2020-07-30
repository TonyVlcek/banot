<?php


declare(strict_types=1);

namespace App\Orm\RootPages;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

/**
 * @method ICollection|RootPage[] findAll()
 * @method RootPage|null getById($id)
 */
class RootPageRepository extends Repository
{
	/**
	 * @inheritDoc
	 */
	public static function getEntityClassNames(): array
	{
		return [RootPage::class];
	}
}
