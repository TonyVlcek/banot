<?php

/**
 * This file is part of the API Service of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

declare(strict_types=1);

namespace App\Search\Documents;

use App\Search\IDocument;
use App\Utils\ISerializable;
use DateTimeImmutable;

final class Item implements IDocument, ISerializable
{
	private string $id;
	private string $url;
	private ?string $photoUrl = null;
	private ?string $title = null;
	private ?string $description = null;
	private ?int $price = null;
	private ?DateTimeImmutable $published = null;

	/** @var string[] */
	private array $labels = [];

	private DateTimeImmutable $created;
	private DateTimeImmutable $lastScraped;
	private ?DateTimeImmutable $deleted = null;


	private function __construct()
	{
	}

	public static function fromHit(array $hit): self
	{
		$item = new self();

		$s = $hit['_source'];

		$item->id = $hit['_id'];
		$item->url = $s['url'];
		$item->photoUrl = $s['photoUrl'] ?? null;
		$item->title = $s['title'] ?? null;
		$item->description = $s['description'] ?? null;
		$item->price = isset($s['price']) ? (int) $s['price'] : null;
		$item->published = isset($s['published']) ? new DateTimeImmutable($s['published']) : null;

		$item->labels = $s['labels'] ?? [];

		$item->created = new DateTimeImmutable($s['created']);
		$item->lastScraped = new DateTimeImmutable($s['lastScraped']);
		$item->deleted = isset($s['deleted']) ? new DateTimeImmutable($s['deleted']) : null;

		return $item;
	}

	public function serialize(): array
	{
		return [
			'url' => $this->url,
			'photoUrl' => $this->photoUrl,
			'title' => $this->title,
			'description' => $this->description,
			'price' => $this->price,
			'published' => $this->published !== null ? $this->published->format(DATE_ISO8601) : null,
			'labels' => $this->labels ?? null,
			'created' => $this->created->format(DATE_ISO8601),
			'lastScraped' => $this->lastScraped->format(DATE_ISO8601),
			'deleted' => $this->deleted !== null ? $this->deleted->format(DATE_ISO8601) : null,
		];
	}

	public function getId(): string
	{
		return $this->id;
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

	/**
	 * @return string[]
	 */
	public function getLabels(): array
	{
		return $this->labels;
	}

	public function getCreated(): DateTimeImmutable
	{
		return $this->created;
	}

	public function getLastScraped(): DateTimeImmutable
	{
		return $this->lastScraped;
	}

	public function getDeleted(): ?DateTimeImmutable
	{
		return $this->deleted;
	}
}
