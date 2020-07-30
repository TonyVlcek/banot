<?php


declare(strict_types=1);

namespace App\Orm\Reports;

use App\Exceptions\EntityNotFoundException;
use App\Orm\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

/**
 * @method ICollection|Report[] findAll()
 * @method Report|null getById($id)
 * @method Report getExistingById($id)
 */
class ReportRepository extends AbstractRepository
{
	/**
	 * @inheritDoc
	 */
	public static function getEntityClassNames(): array
	{
		return [Report::class];
	}

	public function getByEmailAndName(string $email, string $name): ?Report
	{
		return $this->getBy(['email' => $email, 'name' => $name]);
	}

	/**
	 * @throws EntityNotFoundException
	 */
	public function getExistingByEmailAndName(string $email, string $name): Report
	{
		$report = $this->getByEmailAndName($email, $name);

		if ($report === null) {
			throw new EntityNotFoundException("Report {$email}/{$name} not found.");
		}

		return $report;
	}

	public function findReportsToBeNotified(): ICollection
	{
		return $this->findBy(['nextNotification<=' => 'now']);
	}
}
