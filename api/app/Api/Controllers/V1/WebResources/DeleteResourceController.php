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
use App\Exceptions\EntityNotFoundException;
use App\Facades\WebResourceFacade;
use Nette\Http\IResponse;

/**
 * @Path("/resources")
 * @Tag(name="resources")
 */
class DeleteResourceController extends BaseV1Controller
{
	/** @inject */
	public WebResourceFacade $webResourceFacade;


	/**
	 * @Path("/{name}")
	 * @Method("DELETE")
	 * @RequestParameters({
	 *     @RequestParameter(name="name", type="string"),
	 * })
	 */
	public function deleteByName(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$this->webResourceFacade->deleteByName($request->getParameter('name'));
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withCode(IResponse::S404_NOT_FOUND)
				->withMessage($e->getMessage())
				->withPrevious($e);
		}

		return $response->withStatus(IResponse::S204_NO_CONTENT);
	}
}
