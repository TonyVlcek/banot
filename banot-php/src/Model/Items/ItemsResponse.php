<?php declare(strict_types=1);

namespace Banot\SDK\Model\Items;

use Banot\SDK\Model\IApiResponse;

final class ItemsResponse implements IApiResponse
{

	/** @var Item[] */
	private array $items;


	private function __construct()
	{
	}

	public static function create(array $data): self
	{
		$model = new self();

		foreach ($data as $item) {
			$model->items[] = Item::create($item);
		}

		return $model;
	}

	/**
	 * @return Item[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

}
