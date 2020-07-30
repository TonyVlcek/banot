<?php declare(strict_types=1);

namespace Banot\SDK\Api\Reports;

use Banot\SDK\Api\HttpApiAsync;
use Banot\SDK\Exceptions\RuntimeException;
use Banot\SDK\Model\Reports\Report;
use Banot\SDK\Model\Reports\ReportCollection;
use DateTimeImmutable;
use React\Promise\PromiseInterface;

class ReportsAsync extends HttpApiAsync implements IReports
{

	/**
	 * @return PromiseInterface<Report, RuntimeException>
	 */
	public function addReport(string $email, string $query, int $frequency, ?DateTimeImmutable $created = null): PromiseInterface
	{
		return $this->post(
			'/reports',
			[
				'email' => $email,
				'query' => $query,
				'frequency' => $frequency,
				'created' => $created !== null ? $created->format(DATE_ISO8601) : null,
			],
			Report::class
		);
	}

	/**
	 * @return PromiseInterface<Report, RuntimeException>
	 */
	public function getByNameAndEmail(string $email, string $name): PromiseInterface
	{
		return $this->get("/reports/{$email}/{$name}", [], Report::class);
	}

	/**
	 * @return PromiseInterface<Report, RuntimeException>
	 */
	public function findByEmail(string $email): PromiseInterface
	{
		return $this->get("/reports/{$email}", [], ReportCollection::class);
	}
}
