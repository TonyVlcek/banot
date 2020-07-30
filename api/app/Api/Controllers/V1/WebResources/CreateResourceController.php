<?php


declare(strict_types=1);

namespace App\Api\Controllers\V1\WebResources;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Requests\WebResourceRequest;
use App\Exceptions\EntityNotCreatedException;
use App\Facades\WebResourceFacade;
use Nette\Http\IResponse;

/**
 * @Path("/resources")
 * @Tag(name="resources")
 */
class CreateResourceController extends BaseV1Controller
{
	/** @inject */
	public WebResourceFacade $webResourceFacade;


	/**
	 * @Path("/")
	 * @RequestBody(entity="App\Api\Requests\WebResourceRequest")
	 * @Method("POST")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		/** @var WebResourceRequest $resource */
		$resource = $request->getEntity();

		try {
			$this->webResourceFacade->createResource(
				$resource->url,
				$resource->name,
				$resource->instructions,
				$resource->rootPages
			);
		} catch (EntityNotCreatedException $e) {
			throw ClientErrorException::create()
				->withMessage($e->getMessage());
		}


		return $response->withStatus(IResponse::S201_CREATED);
	}

}
