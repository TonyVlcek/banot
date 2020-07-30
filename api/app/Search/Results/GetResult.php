<?php


declare(strict_types=1);

namespace App\Search\Results;

final class GetResult
{

	private string $id;
	private bool $found;
	private array $fields;

	private function __construct()
	{
	}

	public static function create(array $result): self
	{
		$model = new self();

		$model->id = $result['_id'];
		$model->found = $result['found'];
		$model->fields = $model->found ? $result['_source'] : [];

		return $model;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function isFound(): bool
	{
		return $this->found;
	}

	public function getFields(): array
	{
		return $this->fields;
	}

}
