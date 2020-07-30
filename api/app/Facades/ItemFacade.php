<?php


declare(strict_types=1);

namespace App\Facades;

use App\Api\Responses\CollectionResponse;
use App\Exceptions\ElasticSearchQueryFailedException;
use App\Search\Documents\Item;
use App\Search\ItemsIndex;
use App\Search\SearchService;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class ItemFacade
{

	private const MAX_ITEMS_PER_PAGE = 1000; //TODO: env var? (upper limit is 10k https://discuss.elastic.co/t/how-to-elasticsearch-return-all-hits/133373/2)
	private const SCRAPING_FREQUENCY = '24h'; //TODO: env var?

	private SearchService $search;
	private LoggerInterface $logger;
	private ItemsIndex $itemsIndex;


	public function __construct(SearchService $search, LoggerInterface $logger, ItemsIndex $itemsIndex)
	{
		$this->search = $search;
		$this->logger = $logger;
		$this->itemsIndex = $itemsIndex;
	}

	/**
	 * @throws ElasticSearchQueryFailedException
	 */
	public function searchByQuery(string $query, ?DateTimeInterface $createdAfter = null, int $from = 0, int $size = 10): CollectionResponse
	{
		$items = $this->itemsIndex->findByQuery($query, $createdAfter, $from, $size);

		return CollectionResponse::fromSearchCollection($items);
	}

	public function getByUrl(string $url): ?Item
	{
		return $this->itemsIndex->getByUrl($url);
	}

	public function checkWhichItemsNeedScraping(array $urls): CollectionResponse
	{
		$items = $this->itemsIndex->findRecentlyScrapedByUrl($urls, self::SCRAPING_FREQUENCY, self::MAX_ITEMS_PER_PAGE);

		$urlsDontNeedScraping = array_map(fn (Item $item) => $item->getUrl(), $items->getDocuments());

		$needScraping = array_values(array_diff($urls, $urlsDontNeedScraping));

		return new CollectionResponse($needScraping);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function createItem(array $data): string
	{
		Assert::notEmpty($data['url']);

		$now = (new DateTimeImmutable())->format(DATE_ISO8601);

		$data['created'] = $now;
		$data['lastScraped'] = $now;

		$newDocId = $this->itemsIndex->indexDocument($data);

		$this->logger->info("Added new item {$data['url']}. ID: {$newDocId}.");

		return $newDocId;
	}

	public function updateItem(string $id, array $data): int
	{
		$data['lastScraped'] = (new DateTimeImmutable())->format(DATE_ISO8601);

		$response = $this->itemsIndex->updateDocument($id, $data);

		$this->logger->info("Updated item {$data['url']}. ID: {$id}. Version: {$response->getVersion()}.");

		return $response->getVersion();
	}
}
