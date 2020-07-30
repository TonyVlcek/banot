<?php


declare(strict_types=1);

namespace App\Orm\Reports;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;

/**
 * @property int                $id                         {primary}
 * @property string             $name
 * @property string             $email
 * @property string             $query
 * @property int                $frequency
 * @property DateTimeImmutable  $created                    {default now}
 * @property DateTimeImmutable  $lastNotified
 * @property DateTimeImmutable  $nextNotification
 *
 * @property-read string        $humanId                    {virtual}
 */
class Report extends Entity
{
	//TODO: make frequency nullable for paused reports

	protected function getterHumanId(): string
	{
		return sprintf('%s/%s', $this->email, $this->name);
	}
}
