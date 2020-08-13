<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Orm\WebResources;

use App\Exceptions\EntityNotFoundException;
use App\Orm\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @method ICollection|WebResource[] findAll()
 * @method WebResource|null getById($id)
 * @method WebResource getExistingById($id)
 * @method WebResource persistAndFlush(WebResource $resource, bool $withCascade=true)
 */
class WebResourceRepository extends AbstractRepository
{
	/**
	 * @inheritDoc
	 */
	public static function getEntityClassNames(): array
	{
		return [WebResource::class];
	}

	/**
	 * @throws EntityNotFoundException
	 */
	public function getExistingByName(string $name): WebResource
	{
		$resource = $this->getBy(['name' => $name]);

		if ($resource === null) {
			throw new EntityNotFoundException("WebResource named '{$name}' was not found.");
		}

		return $resource;
	}
}
