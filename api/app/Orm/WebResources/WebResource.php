<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Orm\WebResources;

use App\Orm\Instructions\Instruction;
use App\Orm\RootPages\RootPage;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int                          $id               {primary}
 * @property string                       $url
 * @property string                       $name
 *
 * @property OneHasMany|Instruction[]     $instructions     {1:m Instruction::$resource, cascade=[persist, remove]}
 * @property OneHasMany|RootPage[]        $rootPages        {1:m RootPage::$resource, cascade=[persist, remove]}
 */
class WebResource extends Entity
{
}
