<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Dispatchers;

use Apitte\Core\Decorator\DecoratorManager;
use Apitte\Core\Dispatcher\DecoratedDispatcher;
use Apitte\Core\Exception\Logical\InvalidStateException;
use Apitte\Core\Exception\Runtime\EarlyReturnResponseException;
use Apitte\Core\Exception\Runtime\SnapshotException;
use Apitte\Core\Handler\IHandler;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\Http\RequestAttributes;
use Apitte\Core\Mapping\Response\IResponseEntity;
use Apitte\Core\Router\IRouter;
use App\Api\Responses\SerializableResponse;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class SymfonySerializedResponseDispatcher extends DecoratedDispatcher
{
	/** @var SerializerInterface */
	protected $serializer;


	public function __construct(IRouter $router, IHandler $handler, DecoratorManager $decoratorManager, SerializerInterface $serializer)
	{
		parent::__construct($router, $handler, $decoratorManager);
		$this->serializer = $serializer;
	}

	/** @noinspection PhpRedundantCatchClauseInspection */
	protected function handle(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		// Pass endpoint to response
		$endpoint = $request->getAttribute(RequestAttributes::ATTR_ENDPOINT, null);
		if ($endpoint !== null) {
			$response = $response->withEndpoint($endpoint);
		}

		try {
			$request = $this->decoratorManager->decorateRequest($request, $response);
		} catch (EarlyReturnResponseException $exception) {
			return $exception->getResponse();
		}

		try {
			// If exception has been occurred during handling,
			// catch it and take a snapshot (SnapshotException)
			// of current request / response.
			// It's used for passing attributes to next layer (dispatch)
			// from decorators above (IDecorator::HANDLER_BEFORE).
			$result = $this->handler->handle($request, $response);
		} catch (Throwable $e) {
			throw new SnapshotException($e, $request, $response);
		}

		// If result ResponseDTO send serialize to JSON and send with 200 OK code
		// If result is array convert it manually to ArrayEntity,
		// if result is scalar convert it manually to ScalarEntity,
		// if result is IResponseEntity convert it manually to MappingEntity,
		// otherwise use result as response
		if ($result instanceof SerializableResponse) {
			$serialized = $this->serializer->serialize($result, 'json');
			$response = $response
				->withStatus(ApiResponse::S200_OK)
				->withHeader('Content-Type', 'application/json')
				->writeBody($serialized);
		} elseif (is_array($result) || is_scalar($result) || $result instanceof IResponseEntity) {
			$response = $this->negotiate($result, $response);
			//TODO: $serialized = $this->serializer->serialize($data, 'json');
		} else {
			// Validate if response is ResponseInterface
			if (!($result instanceof ResponseInterface)) {
				throw new InvalidStateException(sprintf('Endpoint returned response must implement "%s"', ResponseInterface::class));
			}

			if (!($result instanceof ApiResponse)) { //TODO - deprecation warning
				$result = new ApiResponse($result);
			}

			$response = $result;
		}

		try {
			$response = $this->decoratorManager->decorateResponse($request, $response);
		} catch (EarlyReturnResponseException $exception) {
			return $exception->getResponse();
		}

		return $response;
	}
}
