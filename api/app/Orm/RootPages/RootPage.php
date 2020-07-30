<?php


declare(strict_types=1);

namespace App\Orm\RootPages;

use App\Orm\WebResources\WebResource;
use Nextras\Orm\Entity\Entity;

/**
 * @property int                $id                         {primary}
 * @property string             $url
 * @property string             $name
 *
 * @property WebResource        $resource                    {m:1 WebResource::$rootPages}
 */
class RootPage extends Entity
{
}
