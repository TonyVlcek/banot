<?php


declare(strict_types=1);

namespace App\Search;

use App\Search\Results\UpdateResult;
use Psr\Log\LoggerInterface;

abstract class AbstractIndex
{
	protected SearchService $search;
	protected LoggerInterface $logger;

	public function __construct(SearchService $search, LoggerInterface $logger)
	{
		$this->search = $search;
		$this->search->setIndex('items');
		$this->logger = $logger;
	}

	abstract static function getIndexName(): string;

	public function indexDocument(array $data, ?string $id = null): string
	{
		$result = $this->search->indexDocument($data, $id);

		return $result->getId();
	}

	public function updateDocument(string $id, array $data): UpdateResult
	{
		return $this->search->updateDocument($id, $data);
	}
}
