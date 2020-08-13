<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Responses;

final class ItemSavedRes implements SerializableResponse
{
	public string $id;
	public string $url;
	public int $version;


	public function __construct(string $id, string $url, int $version = 1)
	{
		$this->id = $id;
		$this->url = $url;
		$this->version = $version;
	}
}
