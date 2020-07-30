<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Model;


interface IApiResponse
{
	public static function create(array $data): self; //TODO: static?
}
