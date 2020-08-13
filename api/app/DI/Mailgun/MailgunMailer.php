<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\DI\Mailgun;

use App\DI\Mailgun\Exceptions\LogicException;
use Mailgun\Mailgun;
use Mailgun\Model\Message\SendResponse;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\Mail\MimePart;
use ReflectionException;
use ReflectionObject;

/**
 * Inspired by
 * @see https://github.com/ondrs/nette-mailgun-mailer
 */
class MailgunMailer implements IMailer
{
	private string $domain;

	private Mailgun $mailgun;

	private ?SendResponse $lastResponse;


	public function __construct(string $domain, Mailgun $mailgun)
	{
		$this->domain = $domain;
		$this->mailgun = $mailgun;
	}

	public function send(Message $message): void
	{
		$msg = clone $message;

		$msg->generateMessage();

		$this->lastResponse = $this->mailgun->messages()->send($this->domain, self::formatParams($msg));
	}

	/**
	 * @throws LogicException
	 * @throws ReflectionException
	 */
	private static function formatParams(Message $message): array
	{
		$inlines = self::extractInlines($message);

		$data = [
			'from' => $message->getEncodedHeader('From'),
			'to' => $message->getEncodedHeader('To'),
			'cc' => $message->getEncodedHeader('Cc'),
			'bcc' => $message->getEncodedHeader('Bcc'),
			'subject' => $message->getSubject(),
			'html' => self::formatHtmlBody($message, $inlines),
			'text' => $message->getBody(),
			'attachment' => self::createAttachments($message->getAttachments()),
			'inline' => self::createAttachments($inlines),
		];

		$extraHeaders = ['Reply-To', 'Return-Path'];

		foreach ($extraHeaders as $hdr) {
			if ($encodedHdr = $message->getEncodedHeader($hdr)) {
				$data["h:$hdr"] = $encodedHdr;
			}
		}

		return $data;
	}

	/**
	 * @return MimePart[]
	 * @throws ReflectionException
	 */
	private static function extractInlines(Message $message): array
	{
		$msg = clone $message;

		// Get private inlines
		$ref = new ReflectionObject($msg);
		$inlines = $ref->getProperty('inlines');
		$inlines->setAccessible(true);

		return $inlines->getValue($msg);
	}

	/**
	 * @param MimePart[] $inlines
	 * @throws LogicException
	 */
	private static function formatHtmlBody(Message $message, array $inlines): string
	{
		$htmlBody = $message->getHtmlBody();

		/**
		 * @var string   $filePath
		 * @var MimePart $mimePart
		 */
		foreach ($inlines as $filePath => $mimePart) {
			if (!preg_match('/<(.*?)>/', $mimePart->getHeader('Content-ID'), $cidMatches)) {
				throw new LogicException('Unable match Content-ID');
			}

			$htmlBody = str_replace($cidMatches[1], basename($filePath), $htmlBody);
		}

		return $htmlBody;
	}

	/**
	 * @param MimePart[] $mimeParts
	 * @return array
	 * @throws LogicException
	 */
	private static function createAttachments(array $mimeParts): array
	{
		$arr = [];

		foreach ($mimeParts as $possiblePath => $mimePart) {
			if (!preg_match('/filename="(.*?)"/', $mimePart->getHeader('Content-Disposition'), $nameMatches)) {
				throw new LogicException('Unable to get a filename from the Content-Disposition header');
			}

			if (is_file($possiblePath)) {   // this is inline
				$arr[] = [
					'filename' => $nameMatches[1],
					'filePath' => $possiblePath,
				];
			} else {
				$arr[] = [
					'filename' => $nameMatches[1],
					'fileContent' => $mimePart->getBody(),
				];
			}
		}

		return $arr;
	}
}
