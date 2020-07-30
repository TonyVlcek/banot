<?php

declare(strict_types=1);

namespace Banot\Scraper\Command;

use Banot\Scraper\BunnyManager;
use Banot\SDK\AsyncClient;
use Bunny\Channel;
use Clue\React\Buzz\Browser;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseConsumeCommand extends Command
{
    protected array $config;
    protected LoggerInterface $logger;
    protected LoopInterface $loop;
    protected AsyncClient $banot;
    protected BunnyManager $bunny;
    protected Browser $browser;


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->config = $this->getConfig();
        $this->logger = new ConsoleLogger($output);
        $this->loop = Factory::create();
        $this->bunny = new BunnyManager($this->loop, $this->logger, $this->config['bunny']);
        $this->banot = new AsyncClient($this->loop, $this->config['banotApi']['url'], $this->config['banotApi']['apiKey']);
        $this->browser = (new Browser($this->loop))->withOptions(['timeout' => $this->config['browserTimeout']]);

        $this->consume();

        $this->loop->run();

        return 0;
    }

    abstract protected function consume(): void;

    protected function getConfig(): array
    {
        $envs = getenv();
        return [
            'workerId' => $envs['HOSTNAME'] ?? base64_encode(random_bytes(10)),
            'browserTimeout' => intval($envs['SCRAPER_BROWSER_TIMEOUT'] ?? 15), // seconds
            'bunny' => [
                "host" => $envs['SCRAPER_RABBIT_HOST'],
                "port" => intval($envs['SCRAPER_RABBIT_PORT']),
                "vhost" => $envs['SCRAPER_RABBIT_VHOST'],
                "user" => $envs['SCRAPER_RABBIT_USER'],
                "password" => $envs['SCRAPER_RABBIT_PASSWORD'],
            ],
            'banotApi' => [
                'url' => $envs['SCRAPER_BANOT_URL'],
                'apiKey' => $envs['SCRAPER_BANOT_API_KEY'],
            ],
        ];
    }

    protected function registerSignals(Channel $channel,  string $consumerTag): void
    {
        $this->logger->debug("Registering signals. chID: {$channel->getChannelId()}, coTag: {$consumerTag}");

        $cancel = function () use ($channel, $consumerTag) {
            $this->logger->info("Consumer cancelled");
            $channel->cancel($consumerTag)->done(fn() => exit());
        };

        $this->loop->addSignal(SIGINT, $cancel); // SIGINT = Ctrl+C
        $this->loop->addSignal(SIGTERM, $cancel); // SIGTERM = `kill`
    }
}
