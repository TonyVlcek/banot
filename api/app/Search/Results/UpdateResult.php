<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Search\Results;

final class UpdateResult
{
	private string $id;
	private bool $changed;
	private int $version;


	private function __construct()
	{
	}

	public static function create(array $result): self
	{
		$model = new self();

		$model->id = $result['_id'];
		$model->changed = isset($result['result']) ? ($result['result'] !== 'noop') : true;
		$model->version = $result['_version'];

		return $model;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function isChanged(): bool
	{
		return $this->changed;
	}

	public function getVersion(): int
	{
		return $this->version;
	}
}
