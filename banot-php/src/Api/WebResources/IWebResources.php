<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Api\WebResources;


interface IWebResources
{

	public function getByName(string $name);

	/*
	 * Other endpoints supported by the API
	 * public function findAll();                   GET    /resources
	 * public function create();                    POST   /resources
	 * public function delete(string $name);        DELETE /resources/{name}
	 * public function scrapeOne(string $name);     POST   /resources/{name}/scrape
	 * public function scrapeAll();                 POST   /resources/scrape
	 */

}
