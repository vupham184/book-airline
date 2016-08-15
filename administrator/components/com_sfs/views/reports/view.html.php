<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewReports extends JView
{
	protected $state;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		
		$this->hotels		= $this->get('Hotels');
		$this->airlines		= $this->get('Airlines');
		$this->ghs			= $this->get('Ghs');		
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		require_once JPATH_ROOT.'/components/com_sfs/helpers/field.php';
		
		$this->addToolbar();
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{		
		JToolBarHelper::title(JText::_('Reports'));		
		
		$layout = $this->getLayout();
		
		if($layout == 'hotel')
		{
			$this->hotel = $this->get('Hotel');
			if( !empty($this->hotel) )
			{
				JToolBarHelper::title('Repors for hotel "'.$this->hotel->name.'"');
			}
			
		}
		if($layout == 'airline')
		{
			$this->airline = $this->get('Airline');		
			if( !empty($this->airline) )
			{
				JToolBarHelper::title('Repors for airline "'.$this->airline->airline_name.'"');
			}
			
		}
		
		
		$toolbar = JToolBar::getInstance('toolbar');
		
		$toolbar->appendButton('Link', '', 'Availability report for all hotels', 'index.php?option=com_sfs&view=hotelreporting');
		$toolbar->appendButton('Link', '', 'Report Hotels', 'index.php?option=com_sfs&view=reports&layout=hotels');
		$toolbar->appendButton('Link', '', 'Report Airlines', 'index.php?option=com_sfs&view=airlinereporting');
		$toolbar->appendButton('Link', '', 'Report for Hotel availability', 'index.php?option=com_sfs&view=reports&layout=reportforhotelavailability');
		
		
	}
	
}


