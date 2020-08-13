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
use Apitte\Core\Exception\Api\ServerErrorException;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Api\Controllers\V1\BaseV1Controller;
use App\Exceptions\EntityNotFoundException;
use App\Facades\NotificationFacade;
use Nette\Mail\SendException;

/**
 * @Path("/reports")
 * @Tag(name="reports")
 */
final class NotifyOneController extends BaseV1Controller
{
	/** @inject */
	public NotificationFacade $notificationFacade;


	/**
	 * @Path("/{email}/{name}/notify")
	 * @Method("POST")
	 * @RequestParameters({
	 *      @RequestParameter(name="email", type="string"),
	 *      @RequestParameter(name="name", type="string"),
	 * })
	 */
	public function notifyOne(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$email = $request->getParameter('email');
		$name = $request->getParameter('name');

		try {
			$newItems = $this->notificationFacade->notifyOneReport($email, $name);
		} catch (EntityNotFoundException $e) {
			throw ClientErrorException::create()
				->withCode(ApiResponse::S404_NOT_FOUND)
				->withMessage($e->getMessage());
		} catch (SendException $e) {
			throw ServerErrorException::create()
				->withCode(ApiResponse::S500_INTERNAL_SERVER_ERROR)
				->withMessage('Notification messages failed to send.')
				->withPrevious($e);
		}

		if ($newItems === 0) {
			return $response->withStatus(ApiResponse::S204_NO_CONTENT);
		} else {
			return $response->withStatus(ApiResponse::S200_OK)
				->writeJsonBody(['newItems' => $newItems]);
		}
	}
}
