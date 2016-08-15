<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewImportcsv extends JView
{
	protected $state;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
	
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		parent::display($tpl);
	}
}