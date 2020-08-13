<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Api\Controllers;

use Apitte\Core\Annotation\Controller\Id;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\UI\Controller\IController;

/**
 * @Path("/api")
 * @Id("api")
 */
abstract class BaseController implements IController
{
}
