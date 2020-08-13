<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Controllers\V1\Reports;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\RequestParameters;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Api\Responses\ReportDetailRes;
use App\Facades\ReportFacade;

/**
 * @Path("/reports")
 * @Tag(name="reports")
 */
final class ReportDetailController extends BaseV1Controller
{
	/** @var ReportFacade @inject */
	public $reportFacade;


	/**
	 * @Path("/{email}/{name}")
	 * @Method("GET")
	 * @RequestParameters({
	 *      @RequestParameter(name="email", type="string"),
	 *      @RequestParameter(name="name", type="string"),
	 * })
	 */
	public function detail(ApiRequest $request, ApiResponse $response): ReportDetailRes
	{
		$email = $request->getParameter('email');
		$name = $request->getParameter('name');

		$report = $this->reportFacade->getByEmailAndName($email, $name);

		if ($report === null) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S404_NOT_FOUND)
				->withMessage("Report {$email}/{$name} not found.");
		}

		return ReportDetailRes::fromOrmEntity($report);
	}
}
