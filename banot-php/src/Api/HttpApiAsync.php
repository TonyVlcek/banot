<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api;

use Banot\SDK\Exceptions\RuntimeException;
use Banot\SDK\Exceptions\UnknownErrorException;
use Banot\SDK\Hydrator\IHydrator;
use Clue\React\Buzz\Browser;
use Exception;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\LoopInterface;


abstract class HttpApiAsync
{

	protected Browser $browser;

	private IHydrator $hydrator;

	private LoopInterface $loop;


	public function __construct(Browser $browser, IHydrator $hydrator, LoopInterface $loop)
	{
		$this->browser = $browser;
		$this->hydrator = $hydrator;
		$this->loop = $loop;
	}

	protected function hydrateResponse(ResponseInterface $response, string $class)
	{
		if (200 !== $response->getStatusCode() && 201 !== $response->getStatusCode()) {
			$this->handleErrors($response);
		}

		return $this->hydrator->hydrate($response, $class);
	}


	/* METHODS */

	protected function post(string $endpoint, $body, string $responseClass, array $headers = [])
	{
		return $this->browser->post($endpoint, $headers, json_encode($body))
			->then(
				fn (ResponseInterface $response) => $this->hydrateResponse($response, $responseClass),
				function (Exception $reason) use ($endpoint) {
					throw new RuntimeException("POST Request to {$endpoint} failed.", 0, $reason);
				}
			);
	}

	protected function put(string $endpoint, $body, string $responseClass, array $headers = [])
	{
		return $this->browser->put($endpoint, $headers, json_encode($body))
			->then(
				fn (ResponseInterface $response) => $this->hydrateResponse($response, $responseClass),
				function (Exception $reason) use ($endpoint) {
					throw new RuntimeException("PUT Request to {$endpoint} failed.", 0, $reason);
				}
			);
	}

	protected function get(string $endpoint, array $params, string $responseClass, array $headers = [])
	{
		if (count($params) > 0) {
			$endpoint .= '?'.http_build_query($params);
		}

		return $this->browser->get($endpoint, $headers)
			->then(
				fn (ResponseInterface $response) => $this->hydrateResponse($response, $responseClass),
				function (Exception $reason) use ($endpoint) {
					throw new RuntimeException("GET Request to {$endpoint} failed.", 0, $reason);
				}
			);
	}

	private function handleErrors(ResponseInterface $response): void
	{
		throw new UnknownErrorException("{$response->getStatusCode()} {$response->getReasonPhrase()}");

		/*TODO: User friendly exceptions, e.g.:
		 * switch ($statusCode) {
		 * 	case 400:
		 * 		throw HttpClientException::badRequest($response);
		 * 	case 401:
		 * 		throw HttpClientException::unauthorized($response);
		 * 	case 402:
		 * 		throw HttpClientException::requestFailed($response);
		 * 	case 403:
		 * 		throw  HttpClientException::forbidden($response);
		 * 	case 404:
		 * 		throw HttpClientException::notFound($response);
		 * 	case 413:
		 * 		throw HttpClientException::payloadTooLarge($response);
		 * 	case 500 <= $statusCode:
		 * 		throw HttpServerException::serverError($statusCode);
		 * 	default:
		 * 		throw new UnknownErrorException();
		 * }
		 */
	}

}
