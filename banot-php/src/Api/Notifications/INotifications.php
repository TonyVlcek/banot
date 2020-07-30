<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api\Notifications;


interface INotifications
{

	public function notifyOneReport(string $email, string $name);

	public function notifyAllWaitingReports();

}
