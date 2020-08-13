<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Controllers\V1\WebResources;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Responses\WebResourceDetailRes;
use App\Exceptions\EntityNotFoundException;
use App\Facades\WebResourceFacade;

/**
 * @Path("/resources")
 * @Tag(name="resources")
 */
class ResourceDetailController extends BaseV1Controller
{
	/** @inject */
	public WebResourceFacade $webResourceFacade;


	/**
	 * @Path("/{name}")
	 * @Method("GET")
	 * @RequestParameters({
	 *      @RequestParameter(name="name", type="string", description="WebResource name")
	 * })
	 */
	public function getByName(ApiRequest $request, ApiResponse $response): WebResourceDetailRes
	{
		try {
			$resource = $this->webResourceFacade->getByName($request->getParameter('name'));
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withCode(404)
				->withMessage($e->getMessage())
				->withPrevious($e);
		}

		return WebResourceDetailRes::fromOrmEntity($resource);
	}
}
