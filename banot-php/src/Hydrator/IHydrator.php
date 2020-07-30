<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Hydrator;


use Banot\SDK\Exceptions\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Deserialize a response to something else.
 */
interface IHydrator
{
	/**
	 * @throws HydrationException
	 */
	public function hydrate(ResponseInterface $response, string $class);
}
