<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewDelaycode extends JView
{
	protected $form;
	protected $item;
	protected $state;

	
	public function display($tpl = null)
	{

		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		$isNew		= ($this->item->id == 0);
		JToolBarHelper::title('DelayCode Edit');

		if ($isNew) {
			JToolBarHelper::apply('delaycode.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('delaycode.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('delaycode.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('delaycode.cancel', 'JTOOLBAR_CANCEL');
		}
		else {				
			JToolBarHelper::apply('delaycode.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('delaycode.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('delaycode.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('delaycode.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			JToolBarHelper::cancel('delaycode.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}