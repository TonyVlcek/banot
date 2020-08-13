<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

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
	protected function getterHumanId(): string
	{
		return sprintf('%s/%s', $this->email, $this->name);
	}
}
