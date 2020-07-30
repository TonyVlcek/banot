<?php declare(strict_types=1);

namespace Banot\SDK\Model;

final class EmptyResponse implements IApiResponse
{

	private function __construct()
	{
	}

	public static function create(array $data): IApiResponse
	{
		return new self();
	}

}
