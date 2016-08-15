<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewAirportservices extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->state		= $this->get('State');
		$this->airportcodes		= $this->get('AirportCodes');
		$this->services		= $this->get('Services');
		$this->services_selected = $this->get('AirportServicesSelect');
		//$this->state		= $this->get('State');		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}		
		// Set the toolbar
		$this->addToolBar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{			
		JToolBarHelper::title(JText::_('Airport Services Manager'));
		JToolBarHelper::save('airportservices.save', 'Save');	
		//$toolbar = JToolBar::getInstance('toolbar');	
		//JToolBarHelper::addNew('rentallocation.add','JTOOLBAR_NEW');
		//$toolbar->appendButton('Popup', 'new', 'New', 'index.php?option=com_sfs&view=rentalcar');		
	}
}
