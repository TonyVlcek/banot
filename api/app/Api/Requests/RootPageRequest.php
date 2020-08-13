<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Requests;

use Apitte\Core\Mapping\Request\BasicEntity;

final class RootPageRequest extends BasicEntity
{
	/** @var string */
	public $url;

	/** @var string */
	public $name;
}
