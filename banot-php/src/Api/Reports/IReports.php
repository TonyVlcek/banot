<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api\Reports;

use DateTimeImmutable;


interface IReports
{

	public function addReport(string $email, string $query, int $frequency, ?DateTimeImmutable $created = null);

	public function getByNameAndEmail(string $email, string $name);

	public function findByEmail(string $email);

}
