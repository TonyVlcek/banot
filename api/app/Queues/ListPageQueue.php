<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Queues;

use App\Exceptions\QueueNotDefinedException;
use Contributte\RabbitMQ\Producer\Producer;
use UnexpectedValueException;

final class ListPageQueue
{
	private Producer $producer;


	public function __construct(Producer $producer)
	{
		$this->producer = $producer;
	}

	/**
	 * @throws QueueNotDefinedException
	 * @noinspection PhpRedundantCatchClauseInspection
	 */
	public function publish(string $resourceName, string $rootPageUrl): void
	{
		try {
			$this->producer->publish(sprintf('%s|%s', $resourceName, $rootPageUrl), []);
		} catch (UnexpectedValueException $e) {
			throw new QueueNotDefinedException('The List Page Queue is not defined.', 0, $e);
		}
	}
}
