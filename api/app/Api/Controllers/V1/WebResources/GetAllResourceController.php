<?php


declare(strict_types=1);

namespace App\Api\Controllers\V1\WebResources;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Responses\CollectionResponse;
use App\Api\Responses\WebResourceRes;
use App\Facades\WebResourceFacade;

/**
 * @Path("/resources")
 * @Tag(name="resources")
 */
class GetAllResourceController extends BaseV1Controller
{
	/** @inject */
	public WebResourceFacade $webResourceFacade;


	/**
	 * @Path("/")
	 * @Method("GET")
	 * @RequestParameters({
	 *      @RequestParameter(name="from", type="int", in="query", required=false, description="Pagination offset"),
	 *      @RequestParameter(name="size", type="int", in="query", required=false, description="Pagination limit")
	 * })
	 */
	public function getAll(ApiRequest $request, ApiResponse $response): CollectionResponse
	{
		$from = $request->getParameter('from', null);
		$size = $request->getParameter('size', null);

		$resources = $this->webResourceFacade->findAll($size, $from);

		return CollectionResponse::fromOrmCollection($resources, WebResourceRes::class);
	}

}
