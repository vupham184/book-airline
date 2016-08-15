<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewInvoice extends JView
{	
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		// Initialiase variables.		
		$this->state		= $this->get('State');
		$this->hotel		= $this->get('Hotel');
		$this->merchantFee  = $this->get('MerchantFee');
		$this->reservations	= $this->get('Reservations');
						
								
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
		$toolbar = JToolBar::getInstance('toolbar');
		
		JRequest::setVar('hidemainmenu', true);				
		JToolBarHelper::title($this->hotel->name.': Hotel Invoice Generator');		
		
		$toolbar->appendButton('Link', 'back', 'Back', 'index.php?option=com_sfs&view=hotels');		
	}
}

