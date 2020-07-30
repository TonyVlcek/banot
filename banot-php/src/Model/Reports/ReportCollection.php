<?php declare(strict_types=1);

namespace Banot\SDK\Model\Reports;

use Banot\SDK\Model\IApiResponse;

final class ReportCollection implements IApiResponse
{

	private int $total;

	/** @var Report[] */
	private array $data;


	private function __construct()
	{
	}

	public static function create(array $data): self
	{
		$model = new self();

		$model->total = $data['total'];
		$model->data = [];

		foreach ($data['data'] as $report) {
			$model->data[] = Report::create($report);
		}

		return $model;
	}

	public function getTotal(): int
	{
		return $this->total;
	}

	/**
	 * @return Report[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

}
