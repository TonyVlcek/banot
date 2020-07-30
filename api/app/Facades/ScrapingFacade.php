<?php


declare(strict_types=1);

namespace App\Facades;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\QueueNotDefinedException;
use App\Orm\Orm;
use App\Orm\RootPages\RootPage;
use App\Queues\ListPageQueue;
use Nextras\Orm\Collection\ICollection;
use Psr\Log\LoggerInterface;

class ScrapingFacade
{

	private LoggerInterface $logger;
	private Orm $orm;
	private ListPageQueue $listPageQueue;


	public function __construct(LoggerInterface $logger, Orm $orm, ListPageQueue $listPageQueue) {
		$this->logger = $logger;
		$this->orm = $orm;
		$this->listPageQueue = $listPageQueue;
	}

	/**
	 * @throws EntityNotFoundException
	 * @throws QueueNotDefinedException
	 */
	public function scrapeOneResource(string $name): void
	{
		$resource = $this->orm->webResource->getExistingByName($name);
		$rootPages = $resource->rootPages;

		$this->logger->info("Started scraping of the '{$resource->name}' web resource with {$rootPages->count()} pages.");

		$this->scrapeRootPages($resource->rootPages->get());
	}

	/**
	 * @throws QueueNotDefinedException
	 */
	public function scrapeAllResources(): void
	{
		$resources = $this->orm->webResource->findAll();

		$this->logger->info("Initiated scraping of all '{$resources->count()}' resources.");

		foreach ($resources as $resource) {
			$this->scrapeRootPages($resource->rootPages->get());
		}
	}

	/**
	 * @param RootPage[]|ICollection $rootPages
	 * @throws QueueNotDefinedException
	 */
	protected function scrapeRootPages(ICollection $rootPages): void
	{
		foreach ($rootPages as $page) {
			$this->logger->info("Publishing root page '{$page->name}' ({$page->url}) into the List Page Queue.");

			$this->listPageQueue->publish($page->resource->name, $page->url);
		}
	}
}
