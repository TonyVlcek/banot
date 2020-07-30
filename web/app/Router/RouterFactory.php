<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Route;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

		self::addLocalizedRoute($router, 'report/<email>/<name>', [
			'presenter' => 'ViewReport',
			'action' => 'default',
			'id' => null,
		]);

		self::addLocalizedRoute($router, '<presenter>/<action>[/<id>]', [
			'presenter' => 'Homepage',
			'action' => 'default',
			'id' => null,
		]);

		return $router;
	}

	private static function addLocalizedRoute(RouteList $router, string $mask, $metadata = [], int $flags = 0)
	{

		$metadata['locale'] = [Route::FILTER_TABLE => [
			'cs' => 'cs_CZ',
			'gb' => 'en_GB',
		]];

		$router->addRoute("[!<locale=cs>/]{$mask}", $metadata, $flags);
	}
}
