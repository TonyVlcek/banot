<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Responses;

use App\Orm\WebResources\WebResource;
use Nextras\Orm\Entity\IEntity;

final class WebResourceDetailRes implements SerializableResponse, FromOrmEntityResponse
{
	public int $id;
	public string $url;
	public string $name;
	public array $instructions = [];
	public array $rootPages = [];


	/**
	 * @param IEntity|WebResource $webResource
	 */
	public static function fromOrmEntity(IEntity $webResource): self
	{
		$self = new self();
		$self->id = $webResource->id;
		$self->url = $webResource->url;
		$self->name = $webResource->name;

		$instructions = $webResource->instructions->get()->fetchAll();
		$rootPages = $webResource->rootPages->get()->fetchAll();

		$self->instructions = array_map(fn ($ins) => InstructionRes::fromOrmEntity($ins), $instructions);
		$self->rootPages = array_map(fn ($rp) => $rp->url, $rootPages);

		return $self;
	}
}
