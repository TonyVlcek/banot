<?php


declare(strict_types=1);

namespace App\Search\Results;

final class IndexResult
{

	private string $id;

	private function __construct()
	{
	}

	public static function create(array $response): self
	{
		$model = new self();

		$model->id = $response['_id'];

		return $model;
	}

	public function getId(): string
	{
		return $this->id;
	}

}
