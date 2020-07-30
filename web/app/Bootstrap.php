<?php

declare(strict_types=1);

namespace App;

use Contributte\Bootstrap\ExtraConfigurator;
use Nette\Configurator;


class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new ExtraConfigurator();

		$configurator->addEnvParameters();
		$configurator->setEnvDebugMode();

		$configurator->enableTracy(__DIR__ . '/../log');
		$configurator->setTimeZone('UTC');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(__DIR__ . '/config/common.neon');
		$configurator->addConfig(__DIR__ . '/config/local.neon');

		return $configurator;
	}
}
