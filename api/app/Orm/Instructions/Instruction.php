<?php


declare(strict_types=1);

namespace App\Orm\Instructions;

use App\Orm\WebResources\WebResource;
use Nextras\Orm\Entity\Entity;

/**
 * @property int                $id                         {primary}
 * @property string             $name
 * @property string             $target
 * @property string             $type
 * @property string             $selector
 * @property string|null        $attribute
 * @property string|null        $modifier
 *
 * @property WebResource        $resource                    {m:1 WebResource::$instructions}
 */
class Instruction extends Entity
{
	//TODO: Add enum container: https://blog.nextras.org/orm-3-1-property-containers/
}
