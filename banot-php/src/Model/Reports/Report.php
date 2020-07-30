<?php declare(strict_types=1);

namespace Banot\SDK\Model\Reports;

use Banot\SDK\Model\IApiResponse;
use DateTimeImmutable;

final class Report implements IApiResponse
{

	private string $name;
	private string $email;
	private string $query;
	private int $frequency;
	private DateTimeImmutable $created;
	private ?DateTimeImmutable $lastNotified;


	private function __construct()
	{
	}

	public static function create(array $data): self
	{
		$model = new self();

		$lastNotified = $data['lastNotified'] ?? null;

		$model->name = $data['name'];
		$model->email = $data['email'];
		$model->query = $data['query'];
		$model->frequency = (int) $data['frequency'];
		$model->created = new DateTimeImmutable($data['created']);
		$model->lastNotified = $lastNotified !== null ? new DateTimeImmutable($lastNotified) : null;

		return $model;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getQuery(): string
	{
		return $this->query;
	}

	public function getFrequency(): int
	{
		return $this->frequency;
	}

	public function getCreated(): DateTimeImmutable
	{
		return $this->created;
	}

	public function getLastNotified(): ?DateTimeImmutable
	{
		return $this->lastNotified;
	}

}
