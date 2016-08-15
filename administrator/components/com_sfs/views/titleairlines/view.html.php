<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewTitleAirlines extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $airlines;
	protected $airline;
	protected $options;
	public function display($tpl = null)
	{
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->airlines 	= $this->get('Airlines');	
		$this->airline 		= $this->get('Airline');
		$this->options 		= $this->get('Option');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		echo $optiontitle;		
		parent::display($tpl);
	}

	
	protected function addToolbar()
	{				
		JToolBarHelper::title(JText::_('Title Airline'));
		$toolbar = JToolBar::getInstance('toolbar');	
		$toolbar->appendButton('Popup', 'new', 'New', 'index.php?option=com_sfs&view=titleairlines&layout=airline_title&tmpl=component','550','400','','','');
		JToolBarHelper::deleteList('', 'titleairlines.delete','JTOOLBAR_DELETE');	
		JToolBarHelper::back('Add Option',JRoute::_('index.php?option=com_sfs&view=optiontitles'));	
	}
}
