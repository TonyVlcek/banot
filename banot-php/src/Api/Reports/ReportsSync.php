<?php


declare(strict_types=1);

namespace Banot\SDK\Api\Reports;

use Banot\SDK\Api\HttpApiSync;
use Banot\SDK\Model\Reports\Report;
use Banot\SDK\Model\Reports\ReportCollection;
use DateTimeImmutable;
use function Clue\React\Block\await;

/**
 * @property ReportsAsync $async
 */
class ReportsSync extends HttpApiSync implements IReports
{

	public function addReport(string $email, string $query, int $frequency, ?DateTimeImmutable $created = null): Report
	{
		return await($this->async->addReport($email, $query, $frequency, $created), $this->loop);
	}

	public function getByNameAndEmail(string $email, string $name): Report
	{
		return await($this->async->getByNameAndEmail($email, $name), $this->loop);
	}

	public function findByEmail(string $email): ReportCollection
	{
		return await($this->async->findByEmail($email), $this->loop);
	}
}
