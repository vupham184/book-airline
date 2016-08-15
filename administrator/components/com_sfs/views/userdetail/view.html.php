<?php
defined('_JEXEC') or die;

///jimport('joomla.application.component.view');

class SfsViewUserdetail extends JViewLegacy
{
	protected $item;
	//protected $state;

	public function display($tpl = null)
	{
		$this->item		= $this->get('User');
		//$this->state		= $this->get('State');
		//$this->addToolbar();
		parent::display($tpl);
		JRequest::setVar('hidemainmenu', true);
	}

	protected function addToolbar()
	{	
				
	}
}
