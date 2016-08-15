<?php
defined('_JEXEC') or die;

class SfsViewUpdate extends JViewLegacy
{	

	public function display($tpl = null)
	{			
		$this->state	= $this->get('State');		
		
		$this->currentDBVersion  = $this->get('CurrentDBVersion');
		$this->currentXMLVersion = $this->get('CurrentXMLVersion');
				
		$this->addToolbar();				
		parent::display($tpl);
	}

	protected function addToolbar()
	{			
		JToolBarHelper::title('SFS Updater');		
	}
	
	
	
}

