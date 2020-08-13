<?php

declare(strict_types=1);

namespace Banot\Scraper;

use Banot\Scraper\Parser\Parser;
use Banot\Scraper\Utils\ReasonToString;
use Banot\SDK\AsyncClient;
use Banot\SDK\Model\Items\Item;
use Banot\SDK\Model\WebResources\WebResource;
use Bunny\Async\Client;
use Bunny\Channel;
use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use React\Promise;

class HandleDetailPage
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
		$this->logger->info("Received {$message}");

		//Busy waiting: DoSomeWork::doIt();

		$pending = [
			$this->getParser($message->getResource()),
			$this->getDetailPage($message->getUrl()),
		];

		Promise\all($pending)
			->then(
				function (array $parserAndHtml) use ($message) {
					$this->logger->info('Got parser & HTML');
					/** @var Parser $parser */
					[$parser, $html] = $parserAndHtml;

					return $parser->parse($html, $message->getUrl());
				}
			)
			->then(fn(array $parsedValues) => $this->createItem($parsedValues, $message->getUrl()))
			->then(fn() => $this->bunny->sendAck($message))
			->done(
				fn() => $this->logger->info('Detail page processed'),
				function ($reason) use ($message) {
					$this->logger->error(ReasonToString::convert($reason));
					$this->bunny->sendNack($message)->done();
				}
			);
	}

	/**
	 * @return Promise\PromiseInterface<Parser, null>
	 */
	private function getParser(string $resource): Promise\PromiseInterface
	{
		// TODO: Implement caching of resource instructions - composer require react/cache:^1.0.0

		$this->logger->info("Getting parser for {$resource}");
		$start = hrtime(true);

		return $this->banot->resources()->getByName($resource)
			->then(
				function (WebResource $resource) use ($start) {
					$duration = (hrtime(true) - $start) / 1e+6; //in ms
					$this->logger->debug("[timer] Instructions from banot api retrieved in {$duration} ms.");

					return new Parser($resource->getDetailInstructions());
				}
			);
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

	private function createItem(array $data, string $itemUrl): Promise\PromiseInterface
	{
		$itemData = [];

		$itemData['url'] = $itemUrl;

		// Process standard key:value pairs
		$standardKeys = ['title', 'photoUrl', 'description', 'price', 'published'];
		foreach ($standardKeys as $key) {
			$itemData[$key] = $data[$key] ?? null;
			unset($data[$key]);
		}

		// Process labels - all other non-empty key:value pairs
		$itemData['labels'] = array_filter($data);

		$item = Item::create($itemData);

		return $this->banot->items()->addItem($item);
	}
}
