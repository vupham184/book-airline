<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class SfsViewTimezone extends JView
{
	protected $params;	
	protected $state;
	
	function display($tpl = null)
	{
		// Initialise variables.
		$app		= JFactory::getApplication();		
        $this->user = JFactory::getUser();        
                
		if( SFSAccess::isAirline($this->user) || SFSAccess::isHotel($this->user) ) {
			
		} else {
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=home&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;			
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}		
		
		// Display the view
		parent::display($tpl);
	}
	
}
