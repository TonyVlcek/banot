<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\DI\Mailgun;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class MailgunExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'domain' => Expect::string()->required(),
			'apiKey' => Expect::string()->required(),
			'eu' => Expect::bool(false),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$params = array_filter([
			$this->config->apiKey,
			$this->config->eu ? 'https://api.eu.mailgun.net' : false,
		]);

		$builder->addDefinition($this->prefix('mailgun'))
			->setFactory('Mailgun\Mailgun::create', $params);

		$builder->getDefinition('mail.mailer')
			->setFactory(MailgunMailer::class, [
				$this->config->domain,
				$builder->getDefinition($this->prefix('mailgun')),
			]);

		$builder->addAlias($this->prefix('mailer'), 'mail.mailer');
	}
}
