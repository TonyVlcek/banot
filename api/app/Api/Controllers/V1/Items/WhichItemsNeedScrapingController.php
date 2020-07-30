<?php


declare(strict_types=1);

namespace App\Api\Controllers\V1\Items;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Responses\CollectionResponse;
use App\Facades\ItemFacade;

/**
 * @Path("/items")
 * @Tag(name="items")
 */
class WhichItemsNeedScrapingController extends BaseV1Controller
{
	/** @inject */
	public ItemFacade $itemFacade;


	/**
	 * @Path("/need-scraping")
	 * @Method("POST")
	 */
	public function findItemsWhichNeedScraping(ApiRequest $request, ApiResponse $response): CollectionResponse
	{
		$itemsUrls = $request->getJsonBody();

		return $this->itemFacade->checkWhichItemsNeedScraping($itemsUrls);
	}
}
