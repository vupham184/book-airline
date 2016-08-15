<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class SfsViewLogin extends JView
{
	
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		if( (int) $user->id ) {
			return;
		}
		
		// Display the view
		parent::display($tpl);
	}

}

