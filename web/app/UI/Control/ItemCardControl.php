<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace App\UI\Control;


class ItemCard extends BaseControl
{

	private $item;


	public function __construct($item)
	{
		$this->item = $item;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/itemCard.latte');
		$this->template->item = $this->item;

		$this->template->render();
	}

}

interface IItemCardControlFactory
{
	//TODO: Type once SDK implemented
	public function create($item): ItemCard;
}
