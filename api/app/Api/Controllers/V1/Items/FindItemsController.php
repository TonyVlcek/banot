<?php


declare(strict_types=1);

namespace App\Api\Controllers\V1\Items;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Responses\CollectionResponse;
use App\Exceptions\ElasticSearchQueryFailedException;
use App\Facades\ItemFacade;

/**
 * @Path("/items")
 * @Tag(name="items")
 */
class FindItemsController extends BaseV1Controller
{
	/** @inject */
	public ItemFacade $itemFacade;


	/**
	 * @Path("/")
	 * @Method("GET")
	 * @RequestParameters({
	 *      @RequestParameter(name="query", type="string", in="query", description="Search Query String"),
	 *      @RequestParameter(name="from", type="int", in="query", required=false, description="Pagination offset"),
	 *      @RequestParameter(name="size", type="int", in="query", required=false, description="Pagination limit")
	 * })
	 */
	public function find(ApiRequest $request, ApiResponse $response): CollectionResponse
	{
		$query = $request->getParameter('query');
		$from = $request->getParameter('from', 0);
		$size = $request->getParameter('size', 10);

		$this->logger->debug('Search Query:', [$query]);

		try {
			return $this->itemFacade->searchByQuery($query, null, $from, $size);
		} catch (ElasticSearchQueryFailedException $e) {
			$this->logger->warning($e->getMessage(), ['query' => $query, 'exception' => $e]);
			throw ClientErrorException::create()
				->withMessage("The search query '{$query}' cannot be executed. Please check the syntax.");
		}
	}
}
