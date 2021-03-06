<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

require_once JPATH_ROOT.'/components/com_sfs/helpers/field.php';

class SfsViewHotelreporting extends JView
{
	protected $state;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		
		$this->hotel		= $this->get('Hotel');
						
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{		
		JToolBarHelper::title(JText::_('Hotel availability report'));				
	}
	
}


