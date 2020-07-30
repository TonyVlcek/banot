<?php declare(strict_types=1);

namespace App\Search;

use App\Exceptions\ElasticSearchQueryFailedException;
use App\Search\Results\GetResult;
use App\Search\Results\IndexResult;
use App\Search\Results\SearchResult;
use App\Search\Results\UpdateResult;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Psr\Log\LoggerInterface;

class SearchService
{
	private Client $elastic;
	private LoggerInterface $logger;
	private string $index;


	public function __construct(Client $elastic, LoggerInterface $logger)
	{
		$this->elastic = $elastic;
		$this->logger = $logger;
	}

	public function setIndex(string $index)
	{
		$this->index = $index;
	}

	public function getById(string $id): GetResult
	{
		$result = $this->elastic->get([
			'index' => $this->index,
			'id' => $id
		]);

		return GetResult::create($result);
	}

	/**
	 * @throws ElasticSearchQueryFailedException
	 */
	public function search(array $queryBody, ?string $sort = null, int $from = 0, int $size = 10): SearchResult
	{
		$params = [
			'index' => $this->index,
			'body' => [
				'query' => $queryBody,
			],
			'from' => $from,
			'size' => $size,
		];

		if ($sort !== null) {
			$params['sort'] = $sort;
		}

		$this->logger->debug("Search by query executed.", $params);
		try {
			$search = $this->elastic->search($params);

			return SearchResult::create($search);
		} catch (BadRequest400Exception $e) {
			throw new ElasticSearchQueryFailedException("Something has gone wrong when searching", 0, $e);
		}
	}

	public function searchByTerms(array $terms, int $size = 10): SearchResult
	{
		$must = [];
		foreach ($terms as $term => $value) {
			$must[] = ['term' => [$term => ['value' => $value]]];
		}

		$params = [
			'index' => $this->index,
			'body' => [
				'query' => [
					'bool' => [
						'must' => $must,
					],
				],
			],
			'size' => $size,
		];

		$this->logger->debug("Search by terms executed.", $params);
		$search = $this->elastic->search($params);

		return SearchResult::create($search);
	}

	public function indexDocument(array $document, ?string $id = null): IndexResult
	{
		$params = [
			'index' => $this->index,
			'body'  => $document
		];

		if ($id !== null) {
			$params['id'] = $id;
		}

		$this->logger->debug("Document indexed.", $params);
		return IndexResult::create($this->elastic->index($params));
	}

	public function updateDocument(string $id, array $doc): UpdateResult
	{
		$params = [
			'index' => $this->index,
			'id' => $id,
			'body' => [
				'doc' => $doc
			]
		];

		$this->logger->debug("Document updated.", $params);
		return UpdateResult::create($this->elastic->update($params));
	}
}
