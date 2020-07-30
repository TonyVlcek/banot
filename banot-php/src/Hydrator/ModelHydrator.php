<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Hydrator;

use Banot\SDK\Exceptions\HydrationException;
use Banot\SDK\Model\IApiResponse;
use Psr\Http\Message\ResponseInterface;


final class ModelHydrator implements IHydrator
{

	public function hydrate(ResponseInterface $response, string $class)
	{
		$body = $response->getBody()->__toString();
		$contentType = $response->getHeaderLine('Content-Type');

		if (strpos($contentType, 'application/json') !== 0) {
			throw new HydrationException('The ModelHydrator cannot hydrate response with Content-Type: '.$contentType);
		}

		$data = $body !== '' ? json_decode($body, true) : [];

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new HydrationException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
		}

		if (is_subclass_of($class, IApiResponse::class)) {
			$object = call_user_func($class.'::create', $data);
		} else {
			$object = new $class($data);
		}

		return $object;
	}

}
