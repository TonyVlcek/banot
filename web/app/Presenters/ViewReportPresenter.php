<?php

declare(strict_types=1);

namespace App\Presenters;

use App\UI\Control\IItemListControlFactory;
use App\UI\Control\ItemList;
use Banot\SDK\Client;
use Banot\SDK\Exceptions\RuntimeException;
use Nette\Application\BadRequestException;

final class ViewReportPresenter extends BasePresenter
{

	/** @var Client @inject */
	public Client $banot;

	/** @var IItemListControlFactory @inject */
	public IItemListControlFactory $itemListFactory;

	private string $query;


	public function actionDefault(string $email, string $name)
	{
		try {
			$report = $this->banot->reports()->getByNameAndEmail($email, $name);
		} catch (RuntimeException $e) {
			throw new BadRequestException('Report not found');
		}


		$this->query = $report->getQuery();

		$this->template->query = $this->query;
	}

	protected function createComponentItemList(): ItemList
	{
		$control = $this->itemListFactory->create();
		$control->setQuery($this->query);

		return $control;
	}
}
