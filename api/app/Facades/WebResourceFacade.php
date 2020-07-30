<?php


declare(strict_types=1);

namespace App\Facades;

use App\Api\Requests\InstructionRequest;
use App\Api\Requests\RootPageRequest;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotFoundException;
use App\Model\Instruction\InstructionFactory;
use App\Model\RootPage\RootPageFactory;
use App\Orm\Orm;
use App\Orm\WebResources\WebResource;
use Nextras\Dbal\UniqueConstraintViolationException;
use Nextras\Orm\Collection\ICollection;
use Psr\Log\LoggerInterface;

class WebResourceFacade
{

	private LoggerInterface $logger;

	private Orm $orm;

	private InstructionFactory $instructionFactory;

	private RootPageFactory $rootPageFactory;


	public function __construct(
		LoggerInterface $logger,
		Orm $orm,
		InstructionFactory $instructionFactory,
		RootPageFactory $rootPageFactory
	) {
		$this->logger = $logger;
		$this->orm = $orm;
		$this->instructionFactory = $instructionFactory;
		$this->rootPageFactory = $rootPageFactory;
	}


	/**
	 * @throws EntityNotFoundException
	 */
	public function getById(int $id): WebResource
	{
		return $this->orm->webResource->getExistingById($id);
	}

	/**
	 * @throws EntityNotFoundException
	 */
	public function getByName(string $name): WebResource
	{
		return $this->orm->webResource->getExistingByName($name);
	}

	public function findAll(?int $limit = null, ?int $offset = null): ICollection
	{
		$resources = $this->orm->webResource->findAll();
		if ($limit !== null) {
			$resources->limitBy($limit, $offset);
		}

		return $resources;
	}

	/**
	 * @param string $url
	 * @param string $name
	 * @param InstructionRequest[] $instructions
	 * @param RootPageRequest[]  $rootPages
	 * @return WebResource
	 *
	 * @throws EntityNotCreatedException
	 * @noinspection PhpRedundantCatchClauseInspection
	 */
	public function createResource(string $url, string $name, array $instructions = [], array $rootPages = []): WebResource
	{
		$resource = new WebResource();
		$resource->url = $url;
		$resource->name = $name;

		if ($instructions !== []) {
			foreach ($instructions as $instruction) {
				$resource->instructions->add($this->instructionFactory->createFromRequestDTO($instruction));
			}
		}

		if ($rootPages !== []) {
			foreach ($rootPages as $page) {
				$resource->rootPages->add($this->rootPageFactory->createFromRequestDTO($page));
			}
		}

		try {
			return $this->orm->webResource->persistAndFlush($resource);
		}
		catch (UniqueConstraintViolationException $e) {
			throw new EntityNotCreatedException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * @throws EntityNotFoundException
	 */
	public function deleteByName(string $name): void
	{
		$resource = $this->getByName($name);

		$this->orm->webResource->removeAndFlush($resource);
	}
}
