<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Requests;

use Apitte\Core\Mapping\Request\BasicEntity;

final class WebResourceRequest extends BasicEntity
{
	/** @var string */
	public $url;

	/** @var string */
	public $name;

	/** @var InstructionRequest[] */
	public $instructions = [];

	/** @var RootPageRequest[] */
	public $rootPages = [];


	protected function normalize(string $property, $value)
	{
		if ($property === 'instructions') {
			return array_map(fn ($data) => (new InstructionRequest())->factory($data), $value);
		}

		if ($property === 'rootPages') {
			return array_map(fn ($data) => (new RootPageRequest())->factory($data), $value);
		}

		return parent::normalize($property, $value);
	}
}
