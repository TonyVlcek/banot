<?php


declare(strict_types=1);

namespace App\Facades;

use App\Exceptions\EntityNotFoundException;
use App\Orm\Orm;
use App\Orm\Reports\Report;
use App\Search\ItemsIndex;
use App\Services\MailSender\MailSender;
use DateTimeImmutable;
use Nette\Mail\SendException;
use Psr\Log\LoggerInterface;

class NotificationFacade
{

	private LoggerInterface $logger;
	private Orm $orm;
	private ItemsIndex $itemsIndex;
	private MailSender $mailSender;


	public function __construct(LoggerInterface $logger, Orm $orm, ItemsIndex $itemsIndex, MailSender $mailSender) {
		$this->logger = $logger;
		$this->orm = $orm;
		$this->itemsIndex = $itemsIndex;
		$this->mailSender = $mailSender;
	}

	/**
	 * @throws EntityNotFoundException
	 * @throws SendException
	 */
	public function notifyOneReport(string $email, string $name): int
	{
		$report = $this->orm->report->getExistingByEmailAndName($email, $name);

		return $this->processReportNotification($report);
	}

	/**
	 * @return int
	 * @throws SendException
	 */
	public function notifyAllWaitingReports(): int
	{
		$reports = $this->orm->report->findReportsToBeNotified();
		$numberOfReports = $reports->count();
		$this->logger->info("Starting to process notifications for {$numberOfReports} reports which are waiting for updates.");

		foreach ($reports as $report) {
			$this->processReportNotification($report);
		}

		return $numberOfReports;
	}

	/**
	 * @throws SendException
	 */
	private function processReportNotification(Report $report): int
	{
		$items = $this->itemsIndex->findByQuery($report->query, $report->lastNotified);

		$this->logger->info("Sending notifications for report {$report->humanId}. New items: {$items->getTotal()}.");

		if ($items->getTotal() === 0) {
			return 0;
		}

		$this->mailSender->sendNotification($report, $items);

		$this->markReportAsNotified($report);

		return $items->getTotal();
	}

	private function markReportAsNotified(Report $report)
	{
		$now = new DateTimeImmutable('now');

		$report->lastNotified = $now;
		$report->nextNotification = $now->modify("+ {$report->frequency} hours");

		$this->orm->report->persistAndFlush($report);
	}
}
