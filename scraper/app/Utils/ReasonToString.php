<?php


declare(strict_types=1);

namespace Banot\Scraper\Utils;

class ReasonToString
{
	public static function convert($reason): string
	{
		if ($reason instanceof \Throwable) {
			return (string) $reason; //TODO: better handling of exceptions
		} elseif (is_scalar($reason)) {
			return (string) $reason;
		}

		return var_export($reason, true);
	}
}
