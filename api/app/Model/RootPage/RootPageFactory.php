<?php declare(strict_types=1);

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
