<?php declare(strict_types = 1);

namespace App\Api\Requests;

use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Mapping\Request\BasicEntity;
use DateTimeImmutable;
use Exception;

final class ReportRequest extends BasicEntity
{

	/** @var string @required */
	public $email;

	/** @var string @required */
	public $query;

	/** @var int @required */
	public $frequency;

	/** @var DateTimeImmutable|null */
	public $created;


	protected function normalize(string $property, $value)
	{
		if ($property === 'frequency') {
			return (int) $value;
		}

		if ($property === 'created') {
			try {
				return new DateTimeImmutable($value);
			} catch (Exception $e) {
				throw ClientErrorException::create()
					->withCode(400)
					->withMessage("Cannot format {$property} ({$value}) as DateTime.");
			}
		}

		return parent::normalize($property, $value);
	}

}
