<?php declare(strict_types=1);

/**
 * This file is part of the Banot project (https://banot.cz)
 * Copyright (c) 2020 Tony Vlček
 */

namespace App\UI\Form;

use Contributte\Translation\Translator;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\Checkbox;
use Nette\Localization\ITranslator;


final class FormFactory
{

	/**
	 * @var ITranslator|Translator
	 */
	private ITranslator $translator;

	public function __construct(ITranslator $translator)
	{
		$this->translator = $translator;
	}

	/**
	 * @param string $translatorPrefix domain.key.key
	 * @return BaseForm
	 */
	public function create(string $translatorPrefix = ''): BaseForm
	{
		$form = new BaseForm();
		$formTranslator = clone $this->translator;
		$form->setTranslator($formTranslator->setPrefix(array_filter(explode('.', $translatorPrefix))));
		$form->onRender[] = [$this, 'makeBootstrap4'];

		return $form;
	}


	/**
	 * @internal
	 */
	public function makeBootstrap4(Form $form): void
	{
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = null;
		$renderer->wrappers['pair']['container'] = 'div class="form-group row"';
		$renderer->wrappers['pair']['.error'] = 'has-danger';
		$renderer->wrappers['control']['container'] = 'div class=col-sm-9';
		$renderer->wrappers['label']['container'] = 'div class="col-sm-3 col-form-label"';
		$renderer->wrappers['control']['description'] = 'span class="form-text text-muted"';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=invalid-feedback';
		$renderer->wrappers['control']['.error'] = 'is-invalid';

		foreach ($form->getControls() as $control) {
			$type = $control->getOption('type');
			if ($type === 'button') {
				$control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary');
				$usedPrimary = true;

			} elseif (in_array($type, ['text', 'textarea', 'select'], true)) {
				$control->getControlPrototype()->addClass('form-control');

			} elseif ($type === 'file') {
				$control->getControlPrototype()->addClass('form-control-file');

			} elseif (in_array($type, ['checkbox', 'radio'], true)) {
				if ($control instanceof Checkbox) {
					$control->getLabelPrototype()->addClass('form-check-label');
				} else {
					$control->getItemLabelPrototype()->addClass('form-check-label');
				}
				$control->getControlPrototype()->addClass('form-check-input');
				$control->getSeparatorPrototype()->setName('div')->addClass('form-check');
			}
		}
	}
}
