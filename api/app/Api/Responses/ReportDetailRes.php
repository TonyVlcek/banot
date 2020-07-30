<?php


declare(strict_types=1);

namespace App\Api\Responses;

use App\Orm\Reports\Report;
use DateTimeImmutable;
use Nextras\Orm\Entity\IEntity;

final class ReportDetailRes implements SerializableResponse, FromOrmEntityResponse
{
	public string $email;
	public string $name;
	public string $query;
	public int $frequency;
	public DateTimeImmutable $created;
	public DateTimeImmutable $lastNotified;
	public DateTimeImmutable $nextNotification;


	/**
	 * @param IEntity|Report $report
	 */
	public static function fromOrmEntity(IEntity $report): self
	{
		$self = new self();
		$self->email = $report->email;
		$self->name = $report->name;
		$self->query = $report->query;
		$self->frequency = $report->frequency;
		$self->created = $report->created;
		$self->lastNotified = $report->lastNotified;
		$self->nextNotification = $report->nextNotification;

		return $self;
	}
}
