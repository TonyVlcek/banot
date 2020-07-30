<?php declare(strict_types=1);

namespace Banot\SDK\Model\Items;

use Banot\SDK\Model\IApiResponse;

final class SearchResponse implements IApiResponse
{

	private int $total;

	/**
	 * @var Item[]
	 */
	private array $items;


	private function __construct()
	{
	}

	public static function create(array $response): self
	{
		$model = new self();

		$model->total = $response['total'];
		$model->items = [];

		foreach ($response['data'] as $entity) {
			$model->items[] = Item::create($entity);
		}

		return $model;
	}

	public function getTotal(): int
	{
		return $this->total;
	}

	/**
	 * @return Item[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

}
