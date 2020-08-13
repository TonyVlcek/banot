<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Services\MailSender;

use App\Orm\Reports\Report;
use App\Search\Collection;
use App\Search\Documents\Item;
use Latte\Engine;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Mail\SendException;

class MailSender
{
	private const WEB_BASE_URL = 'https://banot.cz'; //TODO: env variable?
	private const FROM_EMAIL = 'Banot <info@banot.cz>';

	private IMailer $mailer;
	private $latte;


	public function __construct(string $tempDir, IMailer $mailer)
	{
		$this->mailer = $mailer;

		$this->latte = new Engine();
		$this->latte->setTempDirectory($tempDir);
	}

	/**
	 * @throws SendException
	 */
	public function sendConfirmation(Report $report): void
	{
		$params = [
			'name' => $report->name,
			'email' => $report->email,
			'query' => $report->query,
			'frequency' => $report->frequency,
			'link' => sprintf('%s/report/%s/%s', self::WEB_BASE_URL, $report->email, $report->name),
		];

		$mail = new Message();
		$mail->setFrom(self::FROM_EMAIL)
			->addTo($report->email)
			->setSubject("🔔 Banot - Notifikace zapnuty [{$report->name}]")
			->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/confirmation.latte', $params));

		$this->mailer->send($mail);
	}

	/**
	 * @param Item[]|Collection $items
	 * @throws SendException
	 */
	public function sendNotification(Report $report, Collection $items): void
	{
		$newCount = $items->getTotal();
		$newItems = array_slice($items->getDocuments(), 0, 5);

		$params = [
			'count' => $newCount,
			'items' => $newItems,
			'email' => $report->email,
			'name' => $report->name,
			'query' => $report->query,
			'lastNotified' => $report->lastNotified,
			'link' => sprintf('%s/report/%s/%s', self::WEB_BASE_URL, $report->email, $report->name),
		];

		$mail = new Message();
		$mail->setFrom(self::FROM_EMAIL)
			->addTo($report->email)
			->setSubject(sprintf(
				"🎉 Banot - Nalezeno %s nových předmětů [{$report->name}]",
				$newCount > 5 ? $newCount : 'pár'
			))
			->setHtmlBody($this->latte->renderToString(__DIR__ . '/templates/notification.latte', $params));

		$this->mailer->send($mail);
	}
}
