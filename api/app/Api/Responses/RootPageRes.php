<?php


declare(strict_types=1);

namespace App\Api\Responses;

use App\Orm\RootPages\RootPage;
use Nextras\Orm\Entity\IEntity;

final class RootPageRes implements SerializableResponse, FromOrmEntityResponse
{
	public int $id;
	public string $url;
	public string $name;

	/**
	 * @param IEntity|RootPage $rootPage
	 */
	public static function fromOrmEntity(IEntity $rootPage): self
	{
		$self = new self();
		$self->id = $rootPage->id;
		$self->url = $rootPage->url;
		$self->name = $rootPage->name;

		return $self;
	}

}
