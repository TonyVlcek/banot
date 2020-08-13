<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Exceptions;

use UnexpectedValueException;

class UniqueConstraintException extends UnexpectedValueException
{
}
