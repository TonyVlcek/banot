<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Facades;

use App\Exceptions\EntityNotFoundException;
use App\Orm\Orm;
use App\Orm\Reports\Report;
use App\Search\SearchService;
use App\Services\MailSender\MailSender;
use App\Utils\HumanReadableId;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Nette\Mail\SendException;
use Nextras\Orm\Collection\ICollection;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;

class ReportFacade
{
	private SearchService $search;

	private LoggerInterface $logger;

	private Orm $orm;

	private MailSender $mailSender;


	public function __construct(SearchService $search, LoggerInterface $logger, Orm $orm, MailSender $mailSender)
	{
		$this->search = $search;
		$this->logger = $logger;
		$this->orm = $orm;
		$this->mailSender = $mailSender;
	}

	/**
	 * @throws InvalidArgumentException
	 * @throws SendException
	 */
	public function createReport(string $email, string $query, int $frequency, ?DateTimeInterface $created = null): Report
	{
		Assert::email($email);
		Assert::notEmpty($query);

		$now = new DateTimeImmutable('now');

		$report = new Report();
		$report->email = $email;
		$report->name = $this->generateUniqueName($email);
		$report->query = $query;
		$report->frequency = $frequency;
		$report->created = $created ?? $now;
		$report->lastNotified = $now;
		$report->nextNotification = new DateTimeImmutable("+{$frequency} hours");

		$this->orm->report->persistAndFlush($report);

		$this->mailSender->sendConfirmation($report);

		return $report;
	}

	/**
	 * @throws EntityNotFoundException
	 */
	public function getById(int $id): Report
	{
		return $this->orm->report->getExistingById($id);
	}

	/**
	 * @return Report[]|ICollection
	 */
	public function findByEmail(string $email): ICollection
	{
		return $this->orm->report->findBy(['email' => $email]);
	}

	public function getByEmailAndName(string $email, string $name): ?Report
	{
		return $this->orm->report->getByEmailAndName($email, $name);
	}

	/**
	 * Name IN COMBINATION with the email forms a unique id.
	 */
	private function generateUniqueName(string $email): string
	{
		$name = HumanReadableId::generate();

		$report = $this->getByEmailAndName($email, $name);

		if ($report !== null) {
			$this->logger->info("Collision when generating new report name {$email}/{$name} already existed. Generating different name.");

			return $this->generateUniqueName($email);
		}

		return $name;
	}
}
