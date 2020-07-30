<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace Banot\SDK\Model\Items;

use Banot\SDK\Model\IApiEntity;
use Banot\SDK\Model\IApiResponse;
use DateTimeImmutable;
use Exception;


final class Item implements IApiResponse, IApiEntity
{
	private string $url;
	private ?string $photoUrl = null;
	private ?string $title = null;
	private ?string $description = null;
	private ?int $price = null;
	private ?DateTimeImmutable $published = null;
	private array $labels = [];

	private ?DateTimeImmutable $created = null;
	private ?DateTimeImmutable $lastScraped = null;

	private function __construct()
	{
	}

	/**
	 * @throws Exception
	 */
	public static function create(array $data): self
	{
		$model = new self();

		$model->url = (string) $data['url'];
		$model->photoUrl = (string) $data['photoUrl'] ?? null;
		$model->title = (string) $data['title'] ?? null;
		$model->description = (string) $data['description'] ?? null;
		$model->price = (int) $data['price'] ?? null;
		$model->published = $data['published'] !== null ? new DateTimeImmutable($data['published']) : null;
		$model->labels = $data['labels'] ?? [];

		$model->created = $data['created'] !== null ? new DateTimeImmutable($data['created']) : null;
		$model->lastScraped = $data['lastScraped'] !== null ? new DateTimeImmutable($data['lastScraped']) : null;

		return $model;
	}

	public function toArray(): array
	{
		return [
			'url'         => $this->url,
			'photoUrl'    => $this->photoUrl,
			'title'       => $this->title,
			'description' => $this->description,
			'price'       => $this->price,
			'published'   => $this->published ? $this->published->format(DATE_ISO8601) : null,
			'labels'      => $this->labels,
			//$this->created & $this->lastScraped are read only
		];
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getPhotoUrl(): ?string
	{
		return $this->photoUrl;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function getPrice(): ?int
	{
		return $this->price;
	}

	public function getPublished(): ?DateTimeImmutable
	{
		return $this->published;
	}

	public function getLabels(): array
	{
		return $this->labels;
	}

	public function getCreated(): ?DateTimeImmutable
	{
		return $this->created;
	}

	public function getLastScraped(): ?DateTimeImmutable
	{
		return $this->lastScraped;
	}

}
