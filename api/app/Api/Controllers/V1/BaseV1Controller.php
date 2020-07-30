<?php

declare(strict_types = 1);

namespace App\Api\Controllers\V1;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Path;
use App\Api\Controllers\BaseController;
use App\Search\SearchService;
use Psr\Log\LoggerInterface;

/**
 * @Path("/v1")
 * @Id("v1")
 */
abstract class BaseV1Controller extends BaseController
{

	protected SearchService $search;
	protected LoggerInterface $logger;


	public function __construct(SearchService $search, LoggerInterface $logger)
	{
		$this->search = $search;
		$this->logger = $logger;
	}

}
