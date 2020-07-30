<?php


declare(strict_types=1);

namespace App\Search;

interface IDocument
{

	public static function fromHit(array $hit): self;

}
