<?php


declare(strict_types=1);

namespace App\Search;

use App\Exceptions\ElasticSearchQueryFailedException;
use App\Search\Documents\Item;
use DateTimeInterface;

class ItemsIndex extends AbstractIndex
{
	static function getIndexName(): string
	{
		return 'items';
	}

	/**
	 * @throws ElasticSearchQueryFailedException
	 */
	public function findByQuery(string $query, ?DateTimeInterface $createdAfter = null, int $from = 0, int $size = 10): Collection
	{
		$filter = [];
		if ($createdAfter !== null) {
			$filter = [
				'range' => [
					'created' => [
						'gte' => $createdAfter->format(DATE_ISO8601)
					]
				]
			];
		}

		$searchParams = [
			'bool' => [
				'must' => [
					'query_string' => [
						'query' => $query,
						'analyze_wildcard' => true,
					],
				],
				'filter' => $filter
			]
		];

		$result = $this->search->search($searchParams, '_score:desc', $from, $size);

		return Collection::fromHits(Item::class, $result->getHits(), $result->getTotal());
	}

	public function getByUrl(string $url): ?Item
	{
		$result = $this->search->searchByTerms(['url.keyword' => $url]);

		$total = $result->getTotal();
		if ($total === 0) {
			return null;
		} elseif ($total > 1) {
			$this->logger->warning("There are multiple ({$total}) items for {$url}. It must be unique.");
		}

		return Item::fromHit($result->getFirstHit());
	}

	/**
	 * @throws ElasticSearchQueryFailedException
	 */
	public function findRecentlyScrapedByUrl(array $urls, string $scrapingThreshold, int $size = 10): Collection
	{
		$result = $this->search->search([
			'bool' => [
				'filter' => [
					['terms' => ['url.keyword' => $urls]], //TODO: PUT ignore_above 2083 * 4
					['range' => ['lastScraped' => ['gte' => 'now-'.$scrapingThreshold]]],
				],
			],
		], null, 0, $size);

		return Collection::fromHits(Item::class, $result->getHits(), $result->getTotal());
	}
}
