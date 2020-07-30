<?php declare(strict_types=1);

namespace Banot\SDK\Model;


/**
 * Api Entity can be serialized.
 */
interface IApiEntity
{

	public function toArray(): array;

}
