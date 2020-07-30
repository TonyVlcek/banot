<?php declare(strict_types=1);

namespace Banot\SDK\Model\Items;

use Banot\SDK\Model\IApiResponse;


final class ItemsNeedScrapingResponse implements IApiResponse
{

	private int $total;

	/** @var Item[] */
	private array $urls;


	private function __construct()
	{
	}

	public static function create(array $data): self
	{
		$model = new self();

		$model->total = $data['total'];
		$model->urls = $data['data'];

		return $model;
	}

	public function getTotal(): int
	{
		return $this->total;
	}

	/**
	 * @return string[]
	 */
	public function getUrls(): array
	{
		return $this->urls;
	}

}
