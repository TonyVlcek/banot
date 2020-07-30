<?php


declare(strict_types=1);

namespace App\Search\Results;

final class SearchResult
{

	private int $total;
	private array $hits;

	private function __construct()
	{
	}

	public static function create(array $search): self
	{
		$model = new self();

		$model->total = $search['hits']['total']['value'];
		$model->hits = $search['hits']['hits'];

		return $model;
	}

	public function getTotal(): int
	{
		return $this->total;
	}

	public function getHits(): array
	{
		return $this->hits;
	}

	public function getFirstHit(): ?array
	{
		return count($this->hits) > 0 ? $this->hits[0] : null;
	}

	public function getSourceFields(): array
	{
		return array_column($this->hits, '_source');
	}

}
