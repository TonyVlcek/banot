<?php

declare(strict_types=1);

namespace App\Presenters;


use App\UI\Form\FormFactory;
use App\UI\Form\BaseForm;
use Banot\SDK\Client;
use Nette\Utils\ArrayHash;

final class AddReportPresenter extends BasePresenter
{

	/** @var FormFactory @inject */
	public FormFactory $formFactory;

	/** @var Client @inject */
	public Client $banot;

	private string $query;

	public function actionDefault(string $query)
	{
		$search = $this->banot->items()->search($query, 0);
		$this->query = $query;

		$this->template->query = $this->query;
		$this->template->totalCount = $search->getTotal();
	}

	public function renderSuccess(string $email, string $name, string $query, int $frequency)
	{
		$this->template->email = $email;
		$this->template->name = $name;
		$this->template->query = $query;
		$this->template->frequency = $frequency;
	}

	public function createComponentAddReportForm(): BaseForm
	{
		$form = $this->formFactory->create('loc.form_add_report');

		$form->addEmail('email', 'email')
			->setRequired();
		$form->addSelect('frequency', 'frequency', [0 => 'frequency_options.0_hours', 24 => 'frequency_options.24_hours', 168 => 'frequency_options.168_hours']);
		$form->addHidden('query', $this->query);

		$form->addSubmit('save', 'save');
		$form->onSuccess[] = [$this, 'addReportSuccess'];

		return $form;
	}

	public function addReportSuccess(BaseForm $form, ArrayHash $values): void
	{
		// Add report
		$report = $this->banot->reports()->addReport($values->email, $values->query, $values->frequency);

		// Render success message
		$this->redirect('success', [
			'email' => $report->getEmail(),
			'name' => $report->getName(),
			'query' => $report->getQuery(),
			'frequency' => $report->getFrequency()
		]);
	}
}
