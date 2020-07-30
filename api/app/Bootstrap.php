<?php


declare(strict_types=1);

namespace App;

use Contributte\Bootstrap\ExtraConfigurator;

class Bootstrap
{

	public static function boot(): ExtraConfigurator
	{
		$configurator = new ExtraConfigurator();

		$configurator->addEnvParameters();
		$configurator->setEnvDebugMode(); //NETTE_DEBUG env var

		$configurator->enableDebugger(__DIR__ . '/../log');
		$configurator->setTimeZone('UTC');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->addConfig(__DIR__ . '/../config/config.neon');

		// config.local.neon is optional
		$configLocal = __DIR__ . '/../config/config.local.neon';
		if (file_exists($configLocal)) {
			$configurator->addConfig($configLocal);
		}

		return $configurator;
	}

}
