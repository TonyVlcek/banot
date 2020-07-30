<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace App\UI\Control;

use Banot\SDK\Client;
use Nette\Application\UI\Multiplier;


class ItemList extends BaseControl
{

	/** @var int @persistent */
	public int $listSize = 10;

	private ?string $query = null;

	private array $items = [];

	private int $totalCount = 0;

	private int $perPage = 10;

	private Client $banot;

	private IItemCardControlFactory $itemCardFactory;


	public function __construct(Client $banot, IItemCardControlFactory $itemCardFactory)
	{
		$this->banot = $banot;
		$this->itemCardFactory = $itemCardFactory;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/itemList.latte');
		$this->template->items = $this->items;
		$this->template->query = $this->query;
		$this->template->totalCount = $this->totalCount;
		$this->template->showLoadMore = $this->totalCount > $this->listSize;

		$this->template->render();
	}

	protected function createComponentItemCard(): Multiplier
	{
		return new Multiplier(function ($itemId) {
			return $this->itemCardFactory->create($this->items[$itemId]);
		});
	}

	public function getQuery(): ?string
	{
		return $this->query;
	}

	public function setQuery(?string $query): void
	{
		$this->query = $query;

		if ($query !== null && $query !== '') {
			$search = $this->banot->items()->search($this->query, $this->listSize);
			$this->items = $search->getItems();
			$this->totalCount = $search->getTotal();
		} else {
			$this->items = [];
			$this->totalCount = 0;
		}
	}

	public function getItems(): array
	{
		return $this->items;
	}

	public function handleLoadMore(): void
	{
		$this->listSize += $this->perPage;
		$search = $this->banot->items()->search($this->query, $this->listSize);
		$this->items = $search->getItems();

		if ($this->presenter->isAjax()) {
			$this->redrawControl();
		}
	}

}

interface IItemListControlFactory
{
	public function create(): ItemList;
}
