<?php


declare(strict_types=1);

namespace App\Utils;

interface ISerializable
{

	/** @return string|array */
	public function serialize();

}
