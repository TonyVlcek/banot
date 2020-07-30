<?php declare(strict_types=1);

namespace Banot\SDK\Model\WebResources;

final class Instruction
{

	private string $target;

	private string $name;

	private string $type;

	private string $selector;

	private ?string $attribute;

	private ?string $modifier;


	private function __construct()
	{
	}

	public static function create(array $data): self
	{
		$model = new self();

		$model->target = $data['target'];
		$model->name = $data['name'];
		$model->type = $data['type'];
		$model->selector = $data['selector'];
		$model->attribute = $data['attribute'] ?? null;
		$model->modifier = $data['modifier'] ?? null;

		return $model;
	}

	public function getTarget(): string
	{
		return $this->target;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getSelector(): string
	{
		return $this->selector;
	}

	public function getAttribute(): ?string
	{
		return $this->attribute;
	}

	public function getModifier(): ?string
	{
		return $this->modifier;
	}

}
