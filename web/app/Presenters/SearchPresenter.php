<?php declare(strict_types=1);



namespace App\Presenters;

use App\UI\Control\IItemListControlFactory;
use App\UI\Control\ItemList;
use App\UI\Form\BaseForm;
use App\UI\Form\FormFactory;
use Banot\SDK\Client;
use Nette\Utils\ArrayHash;


final class SearchPresenter extends BasePresenter
{

	/** @var Client @inject */
	public $banot;

	/** @var IItemListControlFactory @inject */
	public $itemListFactory;

	/** @var FormFactory @inject */
	public $formFactory;

	/** @var string|null @persistent */
	public ?string $query = null;


	protected function createComponentSearchForm(): BaseForm
	{
		$form = $this->formFactory->create('form_search');

		$form->addText('query', 'query')
			->setDefaultValue($this->query)
			->setRequired();

		$form->addSubmit('search', 'search');

		$form->onSuccess[] = [$this, 'searchSuccess'];

		return $form;
	}

	public function searchSuccess(BaseForm $form, ArrayHash $values): void
	{
		$this->query = $values->query;
		$this['itemList']->setQuery($this->query);

		if ($this->isAjax()) {
			$this->redrawControl('itemListSnippet');
			$this->payload->postGet = true;
			$this->payload->url = $this->link('this');
		} else {
			$this->redirect('this');
		}
	}

	protected function createComponentItemList(): ItemList
	{
		$control = $this->itemListFactory->create();
		$control->setQuery($this->query);

		return $control;
	}

}
