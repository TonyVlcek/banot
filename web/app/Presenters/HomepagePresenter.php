<?php

declare(strict_types=1);

namespace App\Presenters;


use App\UI\Form\FormFactory;
use App\UI\Form\BaseForm;
use Nette\Utils\ArrayHash;

final class HomepagePresenter extends BasePresenter
{

	/** @var FormFactory @inject */
	public FormFactory $formFactory;


	public function createComponentFindReportForm(): BaseForm
	{
		$form = $this->formFactory->create('loc.form_find_report');

		$form->addEmail('email')
			->setRequired();

		$form->addText('name')
			->setRequired();

		$form->addSubmit('open', 'open');
		$form->onSuccess[] = function (BaseForm $form, ArrayHash $values) {
			$this->redirect('ViewReport:', ['email' => $values->email, 'name' => $values->name]);
		};

		return $form;
	}
}
