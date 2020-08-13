<?php

declare(strict_types=1);

namespace Banot\Scraper\Command;

use Banot\Scraper\HandleListPage;
use Banot\Scraper\Utils\ReasonToString;

final class ConsumeListPages extends BaseConsumeCommand
{
	public function configure()
	{
		$this
			->setName('list')
			->setAliases(['list-pages'])
			->setDescription('Starts consuming the List Pages Queue (LPQ).');
	}

	/**
	 * 1. Declare LPQ and DPQ
	 * 2. Start consuming from LPQ
	 */
	protected function consume(): void
	{
		$this->logger->info("List Page Consumer {$this->config['workerId']} started.");

		$this->bunny->connect(1)
			->then(fn() => $this->bunny->declareDPQ())
			->then(fn() => $this->bunny->declareLPQ())
			->then(
				fn() => $this->bunny->consumeLPQ(
					new HandleListPage($this->bunny, $this->logger, $this->banot, $this->browser)
				)
			)
			->done(
				fn($consumerTag) => $this->registerSignals($this->bunny->getChannel(), $consumerTag),
				function ($reason) {
					$this->logger->critical("Connection failed: " . ReasonToString::convert($reason));
					exit(1); //TODO: Attempt reconnect?
				}
			);
	}
}
