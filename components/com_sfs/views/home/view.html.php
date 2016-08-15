<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class SfsViewHome extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

    	//check permision
    	if( SFSAccess::isHotel( $user) ||  SFSAccess::isAirline( $user ) || SFSAccess::isBus() || SFSAccess::isTaxi() )
    	{    		       		                      
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
			return;
    	}

    	if( $user->id ) return;
    	
		// Display the view
		parent::display($tpl);

	}

}

