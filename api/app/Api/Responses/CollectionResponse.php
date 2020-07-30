<?php


declare(strict_types=1);

namespace App\Api\Responses;

use App\Search\Collection;
use Nextras\Orm\Collection\ICollection;

class CollectionResponse implements SerializableResponse
{
	public int $total;
	public array $data;

	public function __construct(array $data, ?int $total = null)
	{
		$this->data = $data;
		$this->total = $total ?? count($data);
	}

	/**
	 * @param FromOrmEntityResponse|string      $responseClass
	 */
	public static function fromOrmCollection(ICollection $collection, string $responseClass): self
	{
		$data = [];
		foreach ($collection as $entity) {
			$data[] = $responseClass::fromOrmEntity($entity);
		}

		return new self($data, $collection->countStored());
	}

	/**
	 * [!] All documents in the Search Collection must be serializable by the Symfony Serializer.
	 */
	public static function fromSearchCollection(Collection $collection): self
	{
		return new self($collection->getDocuments(), $collection->getTotal());
	}
}
