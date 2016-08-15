<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class SfsViewTransportreservations extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{				
		JToolBarHelper::title('Transport Reservations');
		$toolbar = JToolBar::getInstance('toolbar');	
		$session = JFactory::getSession();
		$sessTransport	= $session->get('transport_type','bus');
		if($sessTransport=='bus')
		{
			$toolbar->appendButton('Link', 'busactive', 'Bus Reservations', 'index.php?option=com_sfs&view=transportreservations&transport=bus');	
		} else {
			$toolbar->appendButton('Link', 'bus', 'Bus Reservations', 'index.php?option=com_sfs&view=transportreservations&transport=bus');
		}
		if($sessTransport=='taxi')
		{
			$toolbar->appendButton('Link', 'taxiactive', 'Taxi Reservations', 'index.php?option=com_sfs&view=transportreservations&transport=taxi');	
		} else {
			$toolbar->appendButton('Link', 'taxi', 'Taxi Reservations', 'index.php?option=com_sfs&view=transportreservations&transport=taxi');
		}
		
							
	}
}
