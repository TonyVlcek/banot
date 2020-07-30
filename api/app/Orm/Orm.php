<?php


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
