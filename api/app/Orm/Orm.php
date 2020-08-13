<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Orm;

use App\Orm\Instructions\InstructionRepository;
use App\Orm\Reports\ReportRepository;
use App\Orm\RootPages\RootPageRepository;
use App\Orm\WebResources\WebResourceRepository;
use Nextras\Orm\Model\Model;

/**
 * @property-read InstructionRepository $instruction
 * @property-read ReportRepository      $report
 * @property-read RootPageRepository    $rootPage
 * @property-read WebResourceRepository $webResource
 */
class Orm extends Model
{
}
