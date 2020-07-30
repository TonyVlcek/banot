<?php declare(strict_types = 1);

namespace App\Api\Requests;

use Apitte\Core\Exception\Api\ClientErrorException;
use Apitte\Core\Mapping\Request\BasicEntity;
use DateTimeImmutable;
use Exception;

final class ItemRequest extends BasicEntity
{

	/** @var string */
	public $url;

	/** @var string */
	public $photoUrl;

	/** @var string */
	public $title;

	/** @var string|null */
	public $description;

	/** string|null */
	public $published;

	/** @var int|null */
	public $price;

	/** @var string[] */
	public $labels;


	protected function normalize(string $property, $value)
	{
		if ($property === 'price') {
			return (int) $value;
		}

		if ($property === 'published') {
			try {
				return (new DateTimeImmutable($value))->format(DATE_ISO8601);
			} catch (Exception $e) {
				throw ClientErrorException::create()
					->withCode(400)
					->withMessage("Cannot format {$property} ({$value}) as DateTime.");
			}
		}

		return parent::normalize($property, $value);
	}

}
