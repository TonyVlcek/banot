<?php declare(strict_types=1);

namespace Banot\SDK\Model\WebResources;

use Banot\SDK\Model\IApiEntity;
use Banot\SDK\Model\IApiResponse;

final class WebResource implements IApiResponse, IApiEntity
{

	private string $name;

	private string $url;

	/** @var Instruction[] */
	private array $detailInstructions = [];

	/** @var Instruction[] */
	private array $listInstructions = [];

	/** @var string[] */
	private array $rootPages = [];


	private function __construct()
	{
	}

	public static function create(array $data): self
	{
		$model = new self();

		$model->name = $data['name'];
		$model->url = $data['url'];

		foreach ($data['instructions'] as $instruction) {
			$dto = Instruction::create($instruction);

			if ($dto->getTarget() === 'list') {
				$model->listInstructions[] = $dto;
			} elseif ($dto->getTarget() === 'detail') {
				$model->detailInstructions[] = $dto;
			}
		}

		$model->rootPages = (isset($data['rootPages']) && is_array($data['rootPages'])) ? $data['rootPages'] : [];

		return $model;
	}

	public function toArray(): array
	{
		return [];
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @return Instruction[]
	 */
	public function getDetailInstructions(): array
	{
		return $this->detailInstructions;
	}

	/**
	 * @return Instruction[]
	 */
	public function getListInstructions(): array
	{
		return $this->listInstructions;
	}

	/**
	 * @return string[]
	 */
	public function getRootPages(): array
	{
		return $this->rootPages;
	}

}
