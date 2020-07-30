<?php declare(strict_types = 1);

namespace App\DI\Banot;

use Banot\SDK\Client;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;


/**
 * @property-read stdClass $config
 */
class BanotExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'url' => Expect::string()->required(),
			'apiKey' => Expect::string()->required(),
			'async' => Expect::bool(false)
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('banot'))
			->setFactory(Client::class, [
				'url' => $this->config->url,
				'apiKey' => $this->config->apiKey
			]);
	}

}
