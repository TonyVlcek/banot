<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Controllers\V1\Scraping;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Exceptions\QueueNotDefinedException;
use App\Facades\ScrapingFacade;

/**
 * @Path("/resources")
 * @Tag(name="resources")
 */
class ScrapeAllController extends BaseV1Controller
{
	/** @inject */
	public ScrapingFacade $scrapingFacade;


	/**
	 * @Path("/scrape")
	 * @Method("POST")
	 */
	public function scrapeAll(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		try {
			$this->scrapingFacade->scrapeAllResources();
		} catch (QueueNotDefinedException $e) {
			throw ServerErrorException::create()
				->withCode(ApiResponse::S503_SERVICE_UNAVAILABLE)
				->withMessage('The service is temporarily unavailable, please try it again later.');
		}

		return $response->withStatus(ApiResponse::S200_OK);
	}
}
