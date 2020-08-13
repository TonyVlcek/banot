<?php

declare(strict_types=1);

namespace Banot\Scraper;

use Banot\Scraper\Parser\Parser;
use Banot\Scraper\Utils\ReasonToString;
use Banot\SDK\AsyncClient;
use Banot\SDK\Model\Items\ItemsNeedScrapingResponse;
use Banot\SDK\Model\WebResources\WebResource;
use Bunny\Async\Client;
use Bunny\Channel;
use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use React\Promise;

class HandleListPage
{
	private BunnyManager $bunny;

	private LoggerInterface $logger;

	private AsyncClient $banot;

	private Browser $browser;


	public function __construct(BunnyManager $bunny, LoggerInterface $logger, AsyncClient $banot, Browser $browser)
	{
		$this->bunny = $bunny;
		$this->logger = $logger;
		$this->banot = $banot;
		$this->browser = $browser;
	}

	public function __invoke(\Bunny\Message $message, Channel $channel, Client $bunny): void
	{
		$message = Message::from($message);

		$this->logger->info("Received {$message}: processing LP");

		//Busy waiting: DoSomeWork::doIt();

		$pending = [
			$this->getParser($message->getResource()),
			$this->getDetailPage($message->getUrl()),
		];

		Promise\all($pending)->then(
			function (array $parserAndHtml) use ($message) {
				$this->logger->info('Got parser & HTML');
				/** @var Parser $parser */
				[$parser, $html] = $parserAndHtml;

				return $parser->parse($html, $message->getUrl());
			}
		)->then(
			function (array $extracted) use ($channel, $message) {
				$nextUrl = $extracted['nextUrl'];
				$detailsUrls = $extracted['detailsUrls'];

				$this->publishNextListPage($nextUrl, $message->getResource(), $channel)
					->then(fn() => $this->bunny->sendAck($message))
					->done(
						null,
						fn($reason) => $this->logger->error(
							"Next URL publish failed: " . ReasonToString::convert($reason)
						)
					);

				// Ask which Detail Pages need scraping and Publish them to DPQ
				$this->logger->info('Pushing to DPQ');
				$this->banot->items()->needScraping($detailsUrls)
					->then(
						function (ItemsNeedScrapingResponse $response) use ($channel, $message) {
							$p = [];
							foreach ($response->getUrls() as $detailUrl) {
								$body = "{$message->getResource()}|{$detailUrl}";
								$p[] = $channel->publish(
									$body, [], '', 'DPQ'
								);
							}

							return Promise\all($p);
						}
					)
					->done(
						null,
						fn($reason) => $this->logger->error(
							"Error when publishing details: " . ReasonToString::convert($reason)
						)
					);
			}
		)->done();
	}

	public function publishNextListPage(?string $nextUrl, string $resource, Channel $channel): Promise\PromiseInterface
	{
		if ($nextUrl !== null) {
			//Publish Next List Page and ACK message
			$body = "{$resource}|{$nextUrl}";

			return $channel->publish($body, [], '', 'LPQ');
		}

		$this->logger->info('Reached last page.');

		return new Promise\FulfilledPromise();
	}

	/**
	 * @return Promise\PromiseInterface<Parser, null>
	 */
	private function getParser(string $resource): Promise\PromiseInterface
	{
		// TODO: Implement caching of resource instructions - composer require react/cache:^1.0.0

		$this->logger->info("Getting parser for {$resource}");

		return $this->banot->resources()->getByName($resource)
			->then(fn(WebResource $resource) => new Parser($resource->getListInstructions()));
	}

	/**
	 * @return Promise\PromiseInterface<string, null>
	 */
	private function getDetailPage(string $url): Promise\PromiseInterface
	{
		$start = hrtime(true);

		return $this->browser->get($url)
			->then(
				function (ResponseInterface $r) use ($start, $url): string {
					$duration = (hrtime(true) - $start) / 1e+6; //in ms
					$this->logger->debug("[timer] Request to {$url} took {$duration} ms.");

					return $r->getBody()->getContents();
				}
			);
	}
}
