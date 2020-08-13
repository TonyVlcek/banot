<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Model\RootPage;

use App\Api\Requests\RootPageRequest;
use App\Orm\RootPages\RootPage;

class RootPageFactory
{
	public function createFromRequestDTO(RootPageRequest $page): RootPage
	{
		$newPage = new RootPage();
		$newPage->url = $page->url;
		$newPage->name = $page->name;

		return $newPage;
	}
}
