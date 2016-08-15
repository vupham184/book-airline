<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Airport View
 */
class SfsViewAirports extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Extra layouts
		$layout =& JRequest::getVar('layout', null, 'GET', 'string');
		if( is_readable( dirname(__FILE__).DS.'tmpl'.DS.$layout.'.php' ) ){
			$this->setLayout($layout);
		}
	
		// Get data from the model
		$this->items 		= $this->get('Items');
		$this->pagination   = $this->get('Pagination');
		$this->state		= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();
		
		//Get database object
		$db		= JFactory::getDbo();
		
		//Build country options
		$country_id = (int)$this->state->get('filter.country_id');
		$query	    = $db->getQuery(true);
		$query->select(' a.id As `value`, a.name AS `text` ');
		$query->from(' `#__sfs_country` AS a ');
		$query->order('a.name', 'ASC');
		$db->setQuery($query);
		$countries = $db->loadObjectList();
		$this->options['country'] = JHtml::_('select.options', $countries, 'value', 'text', $country_id);
		
		//Build state options
		$this->options['state'] = null;
		if( $country_id > 0 )
		{
			$query	    = $db->getQuery(true);
			$query->select(' a.id As `value`, a.name AS `text` ');
			$query->from(' `#__sfs_states` AS a ');
			$query->where(" a.country_id = '".$country_id."' ");
			$query->order('a.name', 'ASC');
			$db->setQuery($query);
			$countries = $db->loadObjectList();
			$this->options['state'] = JHtml::_('select.options', $countries, 'value', 'text', (int)$this->state->get('filter.state_id'));
		}
		
		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = sfsHelper::getActions();
		
		JToolBarHelper::title(JText::_('COM_SFS_MANAGER_AIRPORT'), 'airport');
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('airport.add','JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('airport.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::custom('airports.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('airports.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::divider();
			JToolBarHelper::archiveList('airports.archive','JTOOLBAR_ARCHIVE');
			JToolBarHelper::custom('airports.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
		}
		
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'airports.delete','JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('airports.trash','JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_sfs');
			JToolBarHelper::divider();
		}
	}
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_SFS_MANAGER_AIRPORT'));
	}
}
