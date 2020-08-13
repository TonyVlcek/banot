<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Search;

use ArrayIterator;
use IteratorAggregate;

class Collection implements IteratorAggregate
{
	private int $total;
	private array $documents;


	/**
	 * @param IDocument|string $hitClass
	 * @param array[]          $hits
	 * @param int|null         $total When null, number of hits is used as total
	 */
	public static function fromHits(string $hitClass, array $hits, ?int $total = null): self
	{
		$model = new self();

		$model->documents = [];
		foreach ($hits as $hit) {
			$model->documents[] = $hitClass::fromHit($hit);
		}

		$model->total = $total ?? count($hits);

		return $model;
	}

	public function getTotal(): int
	{
		return $this->total;
	}

	public function getDocuments(): array
	{
		return $this->documents;
	}

	public function getIterator()
	{
		return new ArrayIterator($this->documents);
	}
}
