<?php

declare(strict_types=1);

namespace Banot\Scraper\Command;

use Banot\Scraper\HandleDetailPage;
use Banot\Scraper\Utils\ReasonToString;

final class ConsumeDetailPages extends BaseConsumeCommand
{
    public function configure()
    {
        $this
            ->setName('detail')
            ->setAliases(['detail-pages'])
            ->setDescription('Starts consuming the Detail Page Queue (DPQ).');
    }

    /**
     * 1. Declare DPQ
     * 2. Start consuming from DPQ
     */
    protected function consume(): void
    {
        $this->logger->info("Detail Page Consumer {$this->config['workerId']} started.");

        $this->bunny->connect(5)
            ->then(fn () => $this->bunny->declareDPQ())
            ->then(fn () => $this->bunny->consumeDPQ(
                new HandleDetailPage($this->bunny, $this->logger, $this->banot, $this->browser)
            ))
            ->done(
                fn ($consumerTag) => $this->registerSignals($this->bunny->getChannel(), $consumerTag),
                function ($reason) {
                    $this->logger->critical("Connection failed: " . ReasonToString::convert($reason));
                    exit(1); //TODO: attempt reconnect?
                }
            );
    }
}
